<?php
// Routes
use Intervention\Image\ImageManager;

$app->get('/{width}/{height}/{method}/{url:.*}', function ($request, $response, $args) {
    
    $width = (int)$args['width'];
    $height = (int)$args['height'];
    $image_url = $args['url'];
    $methods = array_intersect(array_map("strtoupper", explode("-", $args['method'])), $this->get('settings')['valid_options']);
    $watermark_url = $this->get('settings')['watermark']['image_location'];
    $watermark_status = $this->get('settings')['watermark']['enabled'];
    
    
    if(!is_integer($width) || 
       $width <= 0 || 
       !is_integer($height) || 
       $height <= 0 ||
       !in_array(parse_url($image_url, PHP_URL_HOST), $this->get('settings')['allowed_hosts']) ||
       !count($methods))
    {
        return $response->withStatus(400);
    }
    
    $manager = new ImageManager(['cache' => ['path' => '../cache']]);
    
    $image = $manager->cache(function($image_manager) 
                             use ($width, $height, $image_url, $methods, $watermark_url, $watermark_status, $manager){
        
        $image = $image_manager->make($image_url);
        
        foreach($methods as $method)
        {
            switch($method)
            {
                    Case "RESIZE":
                        $image->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        break;
                    Case "WATERMARK":
                        if($watermark_status)
                        {
                            $watermark = $manager->make($watermark_url);

                            $watermark->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            });

                            $image->insert($watermark);
                        }
            }
        }
        
        return $image;
        
    }, 10, true);

    $response = $response->withHeader('Content-type', 'image/jpg');
    
    return $response->write($image->response('jpg', 70));
});