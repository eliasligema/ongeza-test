<?php

namespace App\Helpers;

use DB;
use App;
use Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Exception;

class Helper
{
    /**
     * Generates a human-readable string describing how long ago a timestamp occurred.
     *
     * @param int $timestamp The timestamp to check.
     * @param int $now       The current time reference point.
     *
     * @return string The time ago in a human-friendly format.
     *
     * @throws Exception if the timestamp is in the future.
     */
    public static function time_ago( $timestamp = 0, $now = 0 ) {

        if(!is_numeric($timestamp)) {
            $timestamp = strtotime($timestamp);
        }

        // Set up our variables.
        $minute_in_seconds = 60;
        $hour_in_seconds   = $minute_in_seconds * 60;
        $day_in_seconds    = $hour_in_seconds * 24;
        $week_in_seconds   = $day_in_seconds * 7;
        $month_in_seconds  = $day_in_seconds * 30;
        $year_in_seconds   = $day_in_seconds * 365;

        // Get the current time if a reference point has not been provided.
        if ( 0 === $now ) {
            $now = time();
        }

        

        // Make sure the timestamp to check is in the past.
        if ( $timestamp > $now ) {
            // dd($timestamp);
            throw new Exception( 'Timestamp is in the future' );
        }

        // Calculate the time difference between the current time reference point and the timestamp we're comparing.
        $time_difference = (int) abs( $now - $timestamp );

        // Calculate the time ago using the smallest applicable unit.
        if ( $time_difference < $hour_in_seconds ) {

            $difference_value = round( $time_difference / $minute_in_seconds );
            $difference_label = 'minute';

        } elseif ( $time_difference < $day_in_seconds ) {

            $difference_value = round( $time_difference / $hour_in_seconds );
            $difference_label = 'hour';

        } elseif ( $time_difference < $week_in_seconds ) {

            $difference_value = round( $time_difference / $day_in_seconds );
            $difference_label = 'day';

        } elseif ( $time_difference < $month_in_seconds ) {

            $difference_value = round( $time_difference / $week_in_seconds );
            $difference_label = 'week';

        } elseif ( $time_difference < $year_in_seconds ) {

            $difference_value = round( $time_difference / $month_in_seconds );
            $difference_label = 'month';

        } else {

            $difference_value = round( $time_difference / $year_in_seconds );
            $difference_label = 'year';
        }

        if ( $difference_value == 1 && $difference_label == 'day') {
            $time_ago = sprintf( 'Yesterday',
                $difference_label
            );
        } elseif ( $difference_value == 0 && $difference_label == 'day' ) {
            $time_ago = sprintf( 'Today',
                $difference_label
            );
        } elseif ( $difference_value <= 1 ) {
            $time_ago = sprintf( 'one %s ago',
                $difference_label
            );
        } else {
            $time_ago = sprintf( '%s %ss ago',
                $difference_value,
                $difference_label
            );
        }

        return $time_ago;
    }

    public static function remain_time($value)
    {
        $date = strtotime($value);
        $remaining = $date - time();

        $days_remaining = floor($remaining / 86400);
        $hours_remaining = floor(($remaining % 86400) / 3600);
        $min_remaining = floor(($remaining % 3600) / 60);
        if($days_remaining > 0){
            return "There are $days_remaining days and $hours_remaining hours left";
        } elseif($hours_remaining > 0) {
            return "$hours_remaining hours and $min_remaining min left";
        } elseif($min_remaining > 0) {
            return "$min_remaining min left";
        }
        
        return "Over due";
    }

    

    public static function friendly_time($value)
    {
        $date = strtotime($value);
        $remaining = $date - time();
        $remaining_in_days = strtotime(date('Y-m-d', $date)) - strtotime(date('Y-m-d', time()));

        if($remaining < 0) {
            return self::time_ago($value);
        }

        $days_remaining = floor($remaining_in_days / 86400);
        $hours_remaining = floor(($remaining % 86400) / 3600);
        $min_remaining = floor(($remaining % 3600) / 60);

        if($days_remaining == 1){
            return "Tommorow @ ".date("H:i a", strtotime($value));
        } elseif($days_remaining > 0){
            return "There are $days_remaining days and $hours_remaining hours left";
        } elseif($hours_remaining > 0) {
            return "Today @ ".date("h:i a", strtotime($value));
        } elseif($min_remaining > 0) {
            return "$min_remaining min left";
        }
        
        return "Now";
    }

    public static function avatar()
    {
        $url = asset('img/avatar-placeholder.jpg');
        return $url;
    }

    public static function hide_some_digits($number)
    {
        $length = strlen($number);
        $hide_length = 4;
        $initial_disp_length = 6;

        return substr($number, 0, $initial_disp_length) 
            . str_repeat( "*", $hide_length )
            . substr($number, ($initial_disp_length + $hide_length), $length);
    }

    public static function generate_random_number()
    {
        return sprintf('%06d', mt_rand(100, 999999));
    }

    public static function phoneFormat($value = null) {
        $value = str_replace(['+', '-', ' '], '', $value);
        if(substr($value, 0, 3) != '255'){
            if(substr($value, 0, 1) == '0'){
                $value = '255'.substr($value, 1);
            } elseif(strlen($value) == 9) {
                $value = '255'.$value;
            }
        }
    
        return $value;
    }

    
    /**
     * @param  Exception  $exception
     *
     * @return string
     */
    public static function logException(Exception $exception)
    {
        return $exception->getMessage()." in ".$exception->getFile()." on line "
            .$exception->getLine();
    }

    public static function save_uploaded_file(Request $request, $key = null, $watermake = 0)
    {
        $filename = null;

        try {
            $file = $request->file($key);
            $filename = self::save_file($file, $watermake);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            $filename = null;
        }

        return $filename;
    }

    public static function save_file($file, $watermake = 0) {
        $filename = null;
        try {
            if($file) {
                $original_name = $file->getClientOriginalName();
                $filename_explode = explode('.', $original_name);
    
                $ext = ($original_name)? array_pop($filename_explode): 'jpg'; 
                $filename = time() . "-" . Str::slug(str_replace('.'.$ext, '', $original_name)).'.'.$ext;
                $file_directory = public_path('uploaded');        
                $file->move($file_directory, $filename);
                $file_path = $file_directory.'/'.$filename;
    
                if($ext != 'svg' && $watermake) {
                    self::add_watermake($file_path, $watermake);
                }
            }
        } catch (\Throwable $th) {
            // dd($e->getMessage());
            $filename = null;
        }
        
        return $filename;
    }

    public static function add_watermake($file_path = null, $size = 0.4)
    {
        if($file_path) {
            $img = Image::make($file_path);
            $water_make_temp = public_path('img/tmp/logo-watermark-temp.png');
            $water_make = Image::make(public_path('img/logo-watermark.png'));
            
            $w_width = (int) round($size*$img->width(), 0);
            $w_height = (int) round($w_width*$water_make->height()/$water_make->width(), 0);
            $water_make->resize($w_width, $w_height)->save($water_make_temp);
    
            $img->insert($water_make_temp, 'center');
            $img->save($file_path); 
        }
    }

    public static function add_thumbnail($file_path = null, $size = 300, $name = 'thumbnail')
    {
        
        $filename = basename($file_path);
        $file_path_array = explode('.', $file_path);
        $ext = ($file_path)? array_pop($file_path_array): 'jpg';
        $img_thumbnail = public_path('uploaded/'.$name.'-'.$filename);
        
        if(is_null($name)) {
            $img_thumbnail = public_path('uploaded/'.$filename);
        }
        

        if($file_path && $ext !== 'svg') {
            try {
                $img = Image::make($file_path);
                $width = (int) $size;
                $height = (int) round($width*$img->height()/$img->width(), 0);

                // dd("W: $width, H: $height");
                $img->resize($width, $height)->save($img_thumbnail);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }


    public static function base64file($request, $name)
    {
        $file = null;
        $data = $name.'_data';
        $filename = $name.'_filename';

        $data_file = (object) [
            'data'=>$request->$data,
            'filename'=>$request->$filename,
        ]; 

        $saved_file = self::base64_to_file([$data_file]);
        if($saved_file){
            $file = public_path('images') . DIRECTORY_SEPARATOR . $saved_file;
        }
        
        return $file;
    }

    private static function base64_to_file($file) 
    {
        $output_file = null;

        $file = (object) ( (is_array($file) && isset($file[0]))? $file[0]: null );

        if($file && isset($file->data) && isset($file->filename)){
            $filename = isset($file->filename)? $file->filename: null;
            $filename_explode = explode('.', $filename);
    
            $ext = ($filename)? array_pop($filename_explode): 'jpg'; 
            $output_file = strtoupper( time() . '-'. uniqid() ) . '.' . $ext;
            $base64_string = isset($file->data)? $file->data: '';
        
            self::save_base64($base64_string, $output_file);
        }
    
        return $output_file; 
    }

    private static function save_base64($base64_string, $output_file) {
        $data = explode( ',', $base64_string );
        if($data && isset($data[1])){
            // open the output file for writing
            $ifp = fopen( public_path('images') .'/'. $output_file, 'wb' );
            // we could add validation here with ensuring count( $data ) > 1
            fwrite( $ifp, base64_decode( $data[ 1 ] ) );
            // clean up the file resource
            fclose( $ifp ); 
        }
    }

    public static function uuid_v3($namespace, $name) {
        if(!self::is_valid($namespace)) return false;

        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-','{','}'), '', $namespace);

        // Binary Value
        $nstr = '';

        // Convert Namespace UUID to bits
        for($i = 0; $i < strlen($nhex); $i+=2) {
            $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
        }

        // Calculate hash value
        $hash = md5($nstr . $name);

        return sprintf('%08s-%04s-%04x-%04x-%12s',

            // 32 bits for "time_low"
            substr($hash, 0, 8),

            // 16 bits for "time_mid"
            substr($hash, 8, 4),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 3
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

            // 48 bits for "node"
            substr($hash, 20, 12)
        );
    }

    public static function uuid_v4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
        }

    
        public static function uuid_v5($namespace, $name) {
        if(!self::is_valid($namespace)) return false;

        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-','{','}'), '', $namespace);

        // Binary Value
        $nstr = '';

        // Convert Namespace UUID to bits
        for($i = 0; $i < strlen($nhex); $i+=2) {
            $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
        }

        // Calculate hash value
        $hash = sha1($nstr . $name);

        return sprintf('%08s-%04s-%04x-%04x-%12s',

            // 32 bits for "time_low"
            substr($hash, 0, 8),

            // 16 bits for "time_mid"
            substr($hash, 8, 4),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 5
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

            // 48 bits for "node"
            substr($hash, 20, 12)
        );
    }

    public static function is_valid($uuid) {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                            '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }    

    public static function query_link($key = null, $value = null, $remove_key = null) 
    {
        $url = request()->url();
        $new_query = '';
        $query = request()->all();
        $query[$key] = $value;
        unset($query['page']);

        if(!$remove_key && request('remove')) {
            $remove_key = request('remove');
        }

        if($remove_key) {
            unset($query['remove']);
            if(isset($query[$remove_key])) {
                unset($query[$remove_key]);
            }
        }

        $new_query = http_build_query($query);

        return $url."?".$new_query;
    }

    public static function notifications(){
        return 0;
    }

    public static function trans($key, $text = null) 
    {
        
        
        return trans($text);
    }

    private static function add_trans($locale, $group, $item, $text) 
    {
        TranslatorTranslation::create([
            'locale' => $locale,
            'namespace' => '*',
            'group' => $group,
            'item' => $item,
            'text' => $text,
            'descriptions' => $text,
            'unstable' => 0,
            'locked	' => 0,
        ]);
    }
}
