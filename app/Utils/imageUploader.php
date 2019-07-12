<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Model;
// use Aws\S3\S3Client;
// use Aws\CloudFront\CloudFrontClient;

class imageUploader extends Model
{
    public static function upload($model,$image,$folder,$type="none",$path="web_data"){
        $extension = !is_string($image) ? $image->getClientOriginalExtension() : "jpg";
        if($type !== "none" && $type !== "")
            $imageFileName = sprintf("%d_%s.%s",$model->id,$type,$extension);
        else
            $imageFileName = sprintf("%d.%s",$model->id,$extension);

        $destino_ftp= sprintf("%s/%s/%s", config('app.path_archive'), $folder, $model->id);	
        @chmod($destino_ftp , 0777);
        if (!file_exists($destino_ftp)) {
            mkdir($destino_ftp, 0777, TRUE);
        }
        $file_path = sprintf("%s/%s", $destino_ftp, $imageFileName);
        @chmod($file_path , 0777);
        if (file_exists($file_path)) {
            @unlink($file_path);
        }
        move_uploaded_file($image, $file_path);
        $path =  sprintf("%s/%s/%s", $folder , $model->id  , $imageFileName);

        return $path;
    }

    public static function uploadFile($model,$image,$folder,$type="none",$path="web_data"){
        $extension = !is_string($image) ? $image->getClientOriginalExtension() : "zip";
        if($type !== "none" && $type !== "")
            $imageFileName = sprintf("%d_%s.%s",$model->id,$type,$extension);
        else
            $imageFileName = sprintf("%d.%s",$model->id,$extension);

        $destino_ftp= sprintf("%s/%s/%s", config('app.path_archive'), $folder, $model->id);
        @chmod($destino_ftp , 0777);
        if (!file_exists($destino_ftp)) {
            mkdir($destino_ftp, 0777, TRUE);
        }
        $file_path = sprintf("%s/%s", $destino_ftp, $imageFileName);
        @chmod($file_path , 0777);
        if (file_exists($file_path)) {
            @unlink($file_path);
        }
        move_uploaded_file($image, $file_path);
        $path =  sprintf("%s/%s/%s", $folder , $model->id  , $imageFileName);

        return $path;
    }

    // public static function upload_s3($model,$image,$folder,$type="none",$parent=null,$path="liga_gamer"){
    //     $amazon_bucket  = config('app.bucket');
    //     $amazon_keyname = config('app.keyname');
    //     $amazon_secret  = config('app.secret');
    //     $amazon_region  = config('app.region');
    //     $amazon_distributionid = config('app.distribution');
    //     $extension = !is_string($image) ? $image->getClientOriginalExtension() : "jpg";
    //     if($type !== "none" && $type !== "")
    //         $imageFileName = sprintf("%d_%s.%s",$model->id,$type,$extension);
    //     else
    //         $imageFileName = sprintf("%d.%s",$model->id,$extension);

    //     $client = CloudFrontClient::factory(array(
    //         'credentials' => array(
    //             'key' => $amazon_keyname,
    //             'secret' => $amazon_secret,
    //             'token' => ''
    //         ),
    //         'region' =>$amazon_region,
    //         'version' => 'latest',
    //     ));

    //     $s3 = new S3Client([
    //         'version' => 'latest',
    //         'region'  => $amazon_region,
    //         'credentials' => array(
    //             'key' => $amazon_keyname,
    //             'secret'  => $amazon_secret,
    //         )
    //     ]);
        
    //     if(is_null($parent))
    //         $destino_ftp = sprintf("%s/%s/%s/%s",$path,$folder,$model->id,$imageFileName);
    //     else
    //         $destino_ftp = sprintf("%s/%s/%s/%s",$path,$folder,$parent->id,$imageFileName);


    //     $s3->putObject([
    //         'Bucket' => $amazon_bucket,
    //         'Key'    => $destino_ftp,
    //         'SourceFile'   => $image,
    //         'ACL'    => 'public-read',
    //     ]);
    //     $result = $client->createInvalidation([
    //         'DistributionId' => $amazon_distributionid,
    //         'InvalidationBatch' => [
    //             'CallerReference' => 'Invalidate for ' . date('Y-m-d-H-i-s') . $imageFileName,
    //             'Paths' => [
    //                 'Quantity' => 1,
    //                 'Items' => [sprintf("/%s",$destino_ftp)],
    //             ],
    //         ],
    //     ]);
    //     return $imageFileName;
    // }
}
