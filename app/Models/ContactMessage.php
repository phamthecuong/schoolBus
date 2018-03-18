<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use Auth;
use App\Models\Users;
class ContactMessage extends Model
{
    use Uuid32ModelTrait;
    protected $tables = 'contact_messages';
    protected $fillable = ['title', 'message'];
    protected $appends = ['email', 'action', 'status_info'];

    public function getEmailAttribute()
    {   
        $parent_email = User::find($this->created_by); 
        if(isset($parent_email))
        {
            return $parent_email->email;    
        }
        
        
    }

    public function getStatusInfoAttribute()
    {
        if ($this->status == 0)
        {
           return trans('contact.not_read'); 
        }
        else 
            return trans('contact.already_read');
        
    }

    public function getActionAttribute()
    {
        // @if (!isset($item['payment_date']))
        // {!! Form::lbButton(url("/school/payment/".$item['id']."/pay"), "GET",trans("payment.payment"), ["class" => "btn btn-xs btn-success", "onclick" => "return confirm('Bạn có muốn thanh toán không?')"]) !!}
        
        // @endif
        if ($this->status == 0)
        {
            return \Form::lbButton(url("/school/contact/".$this->id), "POST",trans("contact.read"), ["class" => "btn btn-xs btn-success", "onclick" => "return confirm('".trans('contact.read_cfm')."')"])->toHtml();

        }
    }       

    public function creator()
    {
        return $this->belongsTo("App\Models\User", "created_by");
    }

    static public function boot()
    {
    	ContactMessage::bootUuid32ModelTrait();
        ContactMessage::saving(function ($comment) {
        	if (Auth::user())
        	{
	            if ($comment->id)
	            {
	            	$comment->updated_by = Auth::user()->id;
	            }
	            else
	            {
					$comment->created_by = Auth::user()->id;
	            }
	        }
        });
    }


}
