<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use File ;
use Helpers ;

class JobProposals extends Model
{
    protected $table = 'job_proposals';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_id','influencer_id', 'cost', 'cover_latter', 'attachments','influencers_hire_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = true;

    public function canHire(){
        $hired = self::where(['job_id' => $this->job_id, 'influencers_hire_status'=> 1])->count();
        return $hired == 0 ? true : false ;
    }


    public function getAttachments(){
        
        $attac = array();

        $array = explode(',', $this->attachments);
        $directory = 'uploads/jobs/attachments';
        $destinationPath = public_path($directory);

        if (count($array)) {
            foreach ($array as $filename) {
                if($filename == ''){
                    continue ;
                }
                $size = (!File::exists( $destinationPath. '/' . $filename)) ? 0 : File::size($destinationPath .'/' . $filename);

                $displayURL = '';
                $ext = pathinfo($destinationPath .'/' . $filename);
                
                switch($ext){
                    case 'pdf' :
                        $displayURL = Helpers::asset('/images/pdf.png');
                        break;
                    case 'doc' :
                    case 'docm' :
                    case 'docx' :
                    case 'dot' :
                    case 'dotm' :
                    case 'dotx' :
                    case 'odt' :
                    case 'rtf' :
                    case 'txt' :
                    case 'wps' :
                    default:
                        $displayURL = Helpers::asset('/images/doc.png');
                        break;
                    case 'mp4':
                    case 'mov':
                    case 'avi':
                        $displayURL = Helpers::asset('/images/video.png');
                        break;
                    case 'png':
                    case 'jpg':
                    case 'jpeg':
                        $displayURL = Helpers::asset($directory. '/'. $filename);

                }

                $attac[] = [
                    'size' => $size,
                    'original' => url($directory .'/' . $filename),
                    'display_url' => $displayURL
                ];
            }
        }
        return json_encode($attac);

    }

    public function user(){
        return $this->hasOne(\App\User::class, 'id', 'influencer_id'); 
    }

    public function job(){
        return $this->hasOne(\App\Models\Job::class, 'id', 'job_id'); 
    }
}
