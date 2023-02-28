<?php

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Option;


function getThemeOptions($optionName){
    $options = Option::where('option_name',$optionName)->pluck('option_value')->first();
    return $optionData = maybe_decode($options);
    
}

function getSettings()
{
    $settingQs = Option::select('option_name','option_value')->get();
    $settings = [];
    foreach ($settingQs as $setting) {
        $settings[$setting->option_name] = maybe_decode($setting->option_value);
    }
    $GLOBALS['settings'] = $settings;
}

function getProjectSection($mainArray,$appendArray){
    if (empty($mainArray)) {
        return $appendArray;
    }
    foreach ($mainArray as $key => $value) {
        if($key=='experince'):
           $appendArray[$key]= (!empty($value)) ? $value : '' ;
        elseif($key=='projectsFinished'):
           $appendArray[$key]= (!empty($value)) ? $value : '' ;
        elseif($key=='clients'):
           $appendArray['clientsCount']= (!empty($value)) ? $value : '' ;
        endif;      
     }
    return $appendArray;
}

function updateOption($optionKey = null, $optionValue = null){
    if ($option = Option::where('option_name', $optionKey)->get()->first()) {
        $option->option_value = maybe_encode($optionValue);
        $option->updated_at = new DateTime;
        $option->save();
    }else{
        $option = new Option;
        $option->option_name = $optionKey;
        $option->option_value = maybe_encode($optionValue);
        $option->created_at = new DateTime;
        $option->updated_at = new DateTime;
        $option->save();
    }
}

function themeFieldArray()
{
    return [
        [
            'key' => 'admin_settings',
            'title' => 'Admin Settings',
            'icon' => '<i class="fa fa-cog" aria-hidden="true"></i></span>',
            'fields' => 
            [
                [
                    'title' =>'Admin E-Mail',
                    'id' => 'admin_email',
                    'type' => 'text',
                    'placeholder' =>'Admin E-Mail',
                    'default' => '',
                ],
                       
            ]
        ],
        [
            'key' => 'header',
            'title' => 'Header',
            'icon' => '<i class="fa fa-cog" aria-hidden="true"></i></span>',
            'fields' => 
            [
                [
                    'title' =>'Header logo',
                    'id' => 'headerlogo',
                    'type' => 'FilesUpload',
                    'slug'=>'header',
                    'placeholder' => 'Upload Logo',
                    'default' => '',
                ],
                [
                    'title' =>'Header description',
                    'id' => 'headerdescription',
                    'type' => 'text',
                    'placeholder' =>'Company Description',
                    'default' => '',
                ],
                [
                    'title' => 'Cart Icon',
                    'type' => 'checkbox',
                    'id' => 'cart_icon',
                    'placeholder' => 'Cart Icon',
                    'options' =>[
                        'yes' => 'Yes'
                    ],
                    'default' => '',
                ],
                       
            ]
        ],
        [
            'key' => 'header_slider_intro',
            'title' => 'Header Slider Intro',
            'icon'=>'<i class="fa fa-cog" aria-hidden="true"></i>',
            'fields' => 
            [
                [
                    'title' => 'Section 1 Icon',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 1 Icon',
                    'id' => 'intro_section_1_icon',
                    'default' => '',
                ],
                [
                    'title' => 'Section 1 Title',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 1 Title',
                    'id' => 'intro_section_1_title',
                    'default' => '',
                ],
                [
                    'title' => 'Section 1 Descriprion',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 1 Descriprion',
                    'id' => 'intro_section_1_description',
                    'default' => '',
                ],
                [
                    'title' => 'Section 2 Icon',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 2 Icon',
                    'id' => 'intro_section_2_icon',
                    'default' => '',
                ],
                [
                    'title' => 'Section 2 Title',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 2 Title',
                    'id' => 'intro_section_2_title',
                    'default' => '',
                ],
                [
                    'title' => 'Section 2 Descriprion',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 2 Descriprion',
                    'id' => 'intro_section_2_description',
                    'default' => '',
                ],
                [
                    'title' => 'Section 3 Icon',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 3 Icon',
                    'id' => 'intro_section_3_icon',
                    'default' => '',
                ],
                [
                    'title' => 'Section 3 Title',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 3 Title',
                    'id' => 'intro_section_3_title',
                    'default' => '',
                ],
                [
                    'title' => 'Section 3 Descriprion',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 1 Descriprion',
                    'id' => 'intro_section_3_description',
                    'default' => '',
                ],
                [
                    'title' => 'Section 4 Icon',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 4 Icon',
                    'id' => 'intro_section_4_icon',
                    'default' => '',
                ],
                [
                    'title' => 'Section 4 Title',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 4 Title',
                    'id' => 'intro_section_4_title',
                    'default' => '',
                ],
                [
                    'title' => 'Section 4 Descriprion',
                    'type' => 'text',
                    'placeholder' => 'Enter Section 4 Descriprion',
                    'id' => 'intro_section_4_description',
                    'default' => '',
                ],
            ]
        ],
        [
            'key' => 'footer',
            'title' => 'Footer',
            'icon'=>'<i class="fa fa-cog" aria-hidden="true"></i>',
            'fields' => 
            [
                [
                    'title' => 'Copy Right',
                    'type' => 'textarea',
                    'placeholder' => 'Enter Copy Right',
                    'id' => 'footer_copy_right',
                    'default' => '',
                ],                
                [
                    'title' => 'Site About Us Title',
                    'type' => 'text',
                    'id' => 'site_about_us_title',
                    'placeholder' => 'Site About Us Title',
                    'default' => '',
                     
                ],                
                [
                    'title' => 'Site About Us Description',
                    'type' => 'text',
                    'id' => 'site_about_us_description',
                    'placeholder' => 'Site About Us Description',
                    'default' => '',
                     
                ],                
                [
                    'title' => 'Facebook',
                    'type' => 'text',
                    'id' => 'facebook',
                    'placeholder' => 'Enter Facebook link',
                    'default' => '',
                     
                ],
                [
                    'title' => 'Twitter',
                    'type' => 'text',
                    'id' => 'twitter',
                    'placeholder' => 'Enter twitter link',
                    'default' => '',
                     
                ],
                [
                    'title' => 'Google Plus',
                    'type' => 'text',
                    'id' => 'gplus',
                    'placeholder' => 'Enter Google Plus link',
                    'default' => '',
                ],
                [
                    'title' => 'Instagram',
                    'type' => 'text',
                    'id' => 'instagram',
                    'placeholder' => 'Enter Instagram link',
                    'default' => '',
                ]
            ],
        ],
        [
            'key' => 'custom_css',
            'title' => 'Custom Css',
            'icon' => '<i class="fa fa-cog" aria-hidden="true"></i>',
            'fields' => [
                [ 
                    'title' => 'Custom css',
                    'type' => 'textareaBig',
                    'placeholder' => 'Enter Your Custom css',
                    'id' => 'custom_css_header',
                    'default' => '',
                ],
            ]
        ],
        [
            'key' => 'payment_getway',
            'title' => 'Payment Getway',
            'icon' => '<i class="fa fa-cog" aria-hidden="true"></i>',
            'fields' => [
                [
                    'title' => 'Enable Stripe',
                    'type' => 'checkbox',
                    'id' => 'enable_stripe',
                    'placeholder' => 'Enable Stripe',
                    'options' =>[
                        'yes' => 'Yes'
                    ],
                    'default' => '',
                ],
                [ 
                    'title' => 'Stripe Key',
                    'type' => 'text',
                    'placeholder' => 'Enter Your Stripe Key',
                    'id' => 'stripe_key',
                    'default' => '',
                ],
                [ 
                    'title' => 'Stripe Secret',
                    'type' => 'text',
                    'placeholder' => 'Enter Your Stripe Secret',
                    'id' => 'stripe_secret',
                    'default' => '',
                ],
                [
                    'title' => 'Stripe Mode',
                    'type' => 'select',
                    'id' => 'stripe_mode',
                    'placeholder' => 'Stripe Mode',
                    'options' =>[
                        'test' => 'Test',
                        'live' => 'Live'
                    ],
                    'default' => '',
                ],
                [
                    'title' => 'Enable Paypal',
                    'type' => 'checkbox',
                    'id' => 'enable_paypal',
                    'placeholder' => 'Enable Paypal',
                    'options' =>[
                        'yes' => 'Yes'
                    ],
                    'default' => '',
                ],
                [ 
                    'title' => 'Paypal Secret',
                    'type' => 'text',
                    'placeholder' => 'Enter Your Paypal Secret',
                    'id' => 'paypal_secret',
                    'default' => '',
                ],
                [ 
                    'title' => 'Paypal Client ID',
                    'type' => 'text',
                    'placeholder' => 'Enter Your Paypal Client ID',
                    'id' => 'paypal_client_id',
                    'default' => '',
                ],
                [
                    'title' => 'Paypal Mode',
                    'type' => 'select',
                    'id' => 'Paypal_mode',
                    'placeholder' => 'Paypal Mode',
                    'options' =>[
                        'test' => 'Test',
                        'live' => 'Live'
                    ],
                    'default' => '',
                ],

                [
                    'title' => 'Enable COD',
                    'type' => 'checkbox',
                    'id' => 'enable_cod',
                    'placeholder' => 'Enable COD',
                    'options' =>[
                        'yes' => 'Yes'
                    ],
                    'default' => '',
                ],
                [
                    'title' => 'Enable EFTPOS',
                    'type' => 'checkbox',
                    'id' => 'enable_eftpos',
                    'placeholder' => 'Enable EFTPOS',
                    'options' =>[
                        'yes' => 'Yes'
                    ],
                    'default' => '',
                ],
                [ 
                    'title' => 'EFTPOS & COD Max Order Amount',
                    'type' => 'number',
                    'placeholder' => 'Enter EFTPOS & COD Max Order Amount',
                    'id' => 'fpos_cod_max_order_amount',
                    'default' => 0,
                ]
            ]
        ]
    ];
}

function FilesUpload($slug,$id,$placeholder,$title,$default,$old){

    return '<div class="col-md-12 imageUploadGroup">
            <label class="col-form-label" for="'.$title.'">'.$title.'</label><br>
            <img src="'.publicPath().'/'.$old.'" class="file-upload" id="'.$slug.'-img" style="width: 100px; height: 100px;">
            <button type="button" data-eid="'.$slug.'" class="btn btn-success setFeaturedImage">Select image</button>
            <button type="button" data-eid="'.$slug.'"  class="btn btn-warning removeFeaturedImage">Remove image</button>
            <input type="hidden" name="'.$id.'" id="'.$slug.'" value="'.$old.'">
        </div>';
}

function number($id,$placeholder,$title,$default,$old){
    return  '<div class="input-group row">
                <label class="col-form-label" for="'.$title.'">'.$title.'</label><br>
                    <input type="text" name="'.$id.'" required="" id="'.$id.'" class="form-control form-control-lg InputNumber" placeholder="'.$placeholder.'" value="'.$old.'">
                    <span class="md-line"></span>
            </div>';
}

function text($id,$placeholder,$title,$default,$old){
    return  '<div class="input-group row">
                <label class="col-form-label" for="'.$title.'">'.$title.'</label><br>
                    <input type="text" name="'.$id.'" required="" id="'.$id.'" class="form-control form-control-lg" placeholder="'.$placeholder.'" value="'.$old.'">
                    <span class="md-line"></span>
            </div>';
}
function email($id,$placeholder,$title,$old){
    return '<div class="input-group row">
                <label class="col-form-labemailel" for="'.$id.'">'.$title.'</label><br>
                    <input type="email" name="'.$id.'" required="" id="'.$id.'" class="form-control form-control-lg" placeholder="'.$placeholder.'" value="'.$old.'">
                    <span class="md-line"></span>
            </div>';

}

function checkbox($id,$placeholder,$title, $options,$old){
    $checkBox = '<div class="input-group row">
    <label class="col-form-label col-md-12" style="padding-left:0px;" for="">'.$title.'</label><br>';
    $count = 0;
    foreach($options as $key => $value){
        $checkBox .='
            <div class="checkbox col-md-12">
              <label for="'.str_slug($id, '-').'-'.$count.'">
                <input type="checkbox" name="'.$id.'" id="'.str_slug($id, '-').'-'.$count.'" value="'.$value.'" '.(($value = $old)?'checked':'').' data-toggle="toggle">
              </label>
            </div>';
            $count++;
    }
    $checkBox .= '<span class="md-line"></span> </div>';
    return  $checkBox;
}
function radio($id,$placeholder,$title, $options,$old){
    $radioButtonArrayData='';
    foreach($options as $key=>$value){
        $count= $key+1;
        $radioButtonArrayData.='<div class="input-group row">
                                    <label class="col-form-label" for="'.$value["id"].'">'.$value["title"].'</label><br>
                                    <input type="radio" name="'.$value["id"].'[]" required="" id="'.$value["id"].'_'.$count.'" class="form-control form-control-lg" value="">
                                    <span class="md-line"></span>
                                </div>';
    }
    return  $radioButtonArrayData;
}
function select($id,$placeholder,$title, $selectOptions,$old){
    $options='';
    foreach($selectOptions as $key=>$value){
        $options.='<option value="'.$key.'" '.($old == $key?'selected':'').'>'.$value.'</option>';
    }

    return '<div class="input-group row">
                 <label class="col-form-label" for="'.str_slug($id, '-').'">'.$title.'</label><br>
                    <select required="" id="'.str_slug($id, '-').'" class="form-control form-control-lg" name="'.$id.'">
                    <option value="">Select</option>
                    '.$options.'
                    </select>
                    <span class="md-line"></span>
            </div>';

}
function textarea($id,$placeholder,$title,$old){
    return '<div class="input-group row">
                <label class="col-form-label" for="'.$id.'">'.$title.'</label><br>
                <textarea name="'.$id.'" required="" id="'.$id.'" class="form-control form-control-lg" placeholder="'.$placeholder.'" rows="5">'.$old.'</textarea>
                <span class="md-line"></span>
            </div>';
}
function textareaBig($id,$placeholder,$title,$old){
    return '<div class="input-group row">
                <label class="col-form-label" for="'.$id.'">'.$title.'</label><br>
                <textarea name="'.$id.'" rows="60" required="" id="'.$id.'" class="form-control form-control-lg" placeholder="'.$placeholder.'" rows="5">'.$old.'</textarea>
                <span class="md-line"></span>
            </div>';
}

function ThemeSidebarOptions(){
    $tabs = themeFieldArray();
	$activeTab = 'active';
	$activeTabContent = 'in active';
	$sidebarTabList = '';
	$sidebarTabContent = '';
	foreach ($tabs as $row) {
        $sidebarTabList .= '<li class="'.$activeTab.'">
                                <a class="input-group" data-toggle="pill" href="#'.$row['key'].'">
                                    <span class="input-group-addon">'.$row['icon'].'</span>
                                    <span>'.$row['title'].'</span>
                                </a>
                            </li>';

        $sidebarTabContent .= '<div id="'.$row['key'].'" class="tab-pane getActive '.$activeTabContent.'">
                                
                                <h3>'.$row['title'].'</h3>';
                                foreach($row['fields'] as $key => $value)
                                {
                                    $oldData = getThemeOptions($row['key']);
                                    $id = $value['id'];
                                    $passingOldData = (isset($oldData[$id])?$oldData[$id]:'');
                                    $sidebarTabContent .=inputFields($row['key'],$value['type'],$value,$passingOldData);
                                }   
                                 
        $sidebarTabContent.='</div>';

		$activeTab = '';
		$activeTabContent = '';
    }
    return '<div class="theme_sidebar">
                <ul class="nav nav-pills" role="tablist">'.$sidebarTabList.'</ul>
            </div>
            <div class="tab-content theme_sidebar_content">  
                '.$sidebarTabContent.'
            </div>';

}

function inputFields($key,$field,$fieldOptions,$oldData){
    $inputName=$key.'['.$fieldOptions['id'].']';
    $inputSlug=$fieldOptions['id'];
    switch($field){
        case 'text':
            return text($inputName,$fieldOptions['placeholder'],$fieldOptions['title'],$fieldOptions['default'],$oldData);
            break;
        
        case 'email':
            return email($inputName,$fieldOptions['placeholder'],$fieldOptions['title'],$fieldOptions['default'],$oldData);
            break;
        
        case 'textarea':
            return textarea($inputName,$fieldOptions['placeholder'],$fieldOptions['title'],$oldData);    
            break; 

        case 'textareaBig':
            return textareaBig($inputName,$fieldOptions['placeholder'],$fieldOptions['title'],$oldData);    
            break; 

        case 'FilesUpload':
            return FilesUpload($inputSlug,$inputName,$fieldOptions['placeholder'],$fieldOptions['title'],$fieldOptions['default'],$oldData);
            break;
        case 'number':
            return number($inputName,$fieldOptions['placeholder'],$fieldOptions['title'],$fieldOptions['default'],$oldData);
            break;
        case 'checkbox':
            return checkbox($inputName,$fieldOptions['placeholder'],$fieldOptions['title'], $fieldOptions['options'],$oldData);    
            break;

        case 'select':
            return select($inputName,$fieldOptions['placeholder'],$fieldOptions['title'], $fieldOptions['options'],$oldData);  
        case 'radio':
                        return;
        default:
                return;                
    }
}
