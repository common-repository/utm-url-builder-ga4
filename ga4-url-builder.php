<?php
/*
 
Plugin Name: UTM - URL Builder for GA4
 
Plugin URI: https://ReallyGoodData.com/tool/plugins/ga4-url-builder
 
Description: The UTM - URL Builder for GA4 lets you add a UTM builder to any page on your site. Activate this plugin, then add this shortcode where you want the form to appear: [add_ga4_utm_builder]
 
Version: 1.0.0
 
Author: ReallyGoodData
 
Author URI: https://ReallyGoodData.com/
 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
Text Domain: reallygooddata
 
*/


add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'utmga4_add_plugin_settings_link');

function utmga4_add_plugin_settings_link( $links ) {
    $links[] = '<a href="https://ReallyGoodData.com/tool/plugins/ga4-url-builder" target="_blank" title="Get Support"> FAQ </a>';
    $links[] = '<a href="https://reallygooddata.com/contact" target="_blank" title="Get Support"> Get Support </a>';
    return $links;
}

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('ga4_utm_builder')){
	class ga4_utm_builder {
		public function __construct(){
			add_shortcode( 'add_ga4_utm_builder', array( $this, 'add_ga4_utm_builder' ) );
		}

        public function inline_css(){ ?>
            <style>
                .ga4-url-generator {
                    max-width: 600px;
                    margin: 0px auto;
                    font-size: 16px;
                }
                .ga4-url-generator .utmga4_of-field,
                .ga4-url-generator .utmga4_of-field label,
                .ga4-url-generator .utmga4_of-field input,
                .ga4-url-generator .utmga4_of-field select,
                .ga4-url-generator .utmga4_of-output textarea {
                    width: 100%;
                    float: left;
                    display: block;
                }
                .ga4-url-generator .utmga4_of-field input {
                    min-height: 40px;
                    border-radius: 3px;
                    border: 1px solid #000000;
                    text-transform: lowercase;
                }
                .ga4-url-generator .utmga4_of-field select {
                    min-height: 40px;
                    border-radius: 3px;
                    border: 1px solid #000000;
                }
                .utmga4_of-field fieldset {
                    display: block;
                    margin-inline-start: 1px;
                    margin-inline-end: 1px;
                    padding-block-start: 0;
                    padding-inline-start: 0.15em;
                    padding-inline-end: 0.15em;
                    padding-block-end: 0;
                    min-inline-size: min-content;
                    border-width: 1px;
                    border-style: groove;
                    border-color: #000000;
                    border-image: initial;
                    border-radius: 3px;
                }
                .utmga4_of-field fieldset legend {
                    line-height: 14px;
					padding-left: 10px;
   					padding-right: 10px;
                }
                .utmga4_regex_rules {  
                    background-color: #F4F3F2;
                    padding: 25px;
                   font-size: 14px;
                   line-height: 18px;   
                   border-radius: 20px               
                }
                
                .ga4-url-generator .utmga4_of-field input,
                .ga4-url-generator .utmga4_of-field textarea {
                    border-color: #CCCCCC;
                    border: 0px;
					padding-left: 10px;
                    font-size: 14px;

                    
                }
                .ga4-url-generator .utmga4_of-field {
                    margin-bottom: 20px;
					padding-left: 10px;
                }
                .ga4-url-generator .utmga4_of-field span {
                    display: block;
                    font-size: 12px;
                    width: 100%;
                    float: left;
                    line-height: 12px;
                    margin-top: 8px;
                }
				.ga4-url-generator .utmga4_of-field select {
  					min-height: 40px;
    				border-radius: 3px;
    				border: 2px solid #ff8a00;
    				padding-left: 10px;
					max-width: 250px;
                    font-size: 16px;

				}
                textarea#utmga4_of-cp_url {
					background: #ffe6cc;
					padding: 10px;
					text-align: center;
					font-size: 18px;
					font-weight: 600;
					border: 2px solid #ff8a00;
					border-radius: 5px;
					text-transform: lowercase;
					height: 150px;
                }
                a#utmga4_utmga4_of-cp_url {
                    display: inline-block;
                    background: #ff8a00;
                    padding: 5px 30px;
                    color: #ffffff;
                    font-size: 18px;
                    border-radius: 5px;
                    margin-bottom: 10px;
                    float: right;
                }
                span#utmga4_of-url-copied {
                    width: auto;
                    float: right;
					margin-right:20px;
					margin-top: 5px;
                }
                .utmga4_of-field p {
                    width: 100%;
                    float: left;
                    text-align: right;
                    margin-bottom: 30px;
                }
                #utmga4_credit {
                    font-size: 12px;
                    text-align: right; 

                }
                #utmga4_credit a {
                    font-size: 12px;
                    color: #044e93; 
                }
               
                

            </style>
        <?php }
		
        public function inline_js(){?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script>
            jQuery(document).ready(function($){
                function utmga4_copyToClipboard(text) {
                    var utmga4_textArea = document.createElement( "textarea" );
                    utmga4_textArea.value = text;
                    document.body.appendChild( utmga4_textArea );
                    utmga4_textArea.select();

                    try {
                        var utmga4_successful = document.execCommand( 'copy' );
                        var utmga4_msg = successful ? 'successful' : 'unsuccessful';
                    } catch (err) {
                    }    
                    document.body.removeChild( utmga4_textArea );
                }
					
                $("#utmga4_cp-channel").change(function(){
                    var utmga4_currObj = $(this);
                    var utmga4_channel = $(utmga4_currObj).val();
                    var utmga4_selected_option = $("#utmga4_cp-channel").find(":selected");
                    if(utmga4_channel == ""){
                        $(".utmga4_of-field span").html("");
                        $("#utmga4_of-cp_url, .of-input input").val("");
                    } else {
                        $(".source input").attr("placeholder", $(utmga4_selected_option).data("source_placeholder"));
                        $(".source span").html($(utmga4_selected_option).data("source_rule"));
                        $(".medium input").attr("placeholder", $(utmga4_selected_option).data("medium_placeholder"));
                        $(".medium span").html($(utmga4_selected_option).data("medium_rule"));
                        $(".campaign input").attr("placeholder", $(utmga4_selected_option).data("campaign_placeholder"));
                        $(".campaign span").html($(utmga4_selected_option).data("campaign_rule"));
                        $(".utmga4_regex_rules span").html($(utmga4_selected_option).data("regex_rules"));
                    }
                });

                $(".utmga4_of-field input[type='text']").keyup(function(){
                    var utmga4_channel = $("#utmga4_cp-channel").val();
                    if(utmga4_channel == ""){
                        $("#utmga4_of-cp_url").val("");
                    } else {
                        var utmga4_cp_url = $("#utmga4_cp-url").val();
                        var utmga4_cp_cp_source = $("#utmga4_cp-cp_source").val();
                        var utmga4_cp_cp_medium = $("#utmga4_cp-cp_medium").val();
                        var utmga4_cp_cp_name = $("#utmga4_cp-cp_name").val();
                        if(utmga4_cp_url == ""){
                            $("#utmga4_of-cp_url").val("");
                        } else {
                            var utmga4_url_arr = [];
                            if(utmga4_cp_cp_source != "" && utmga4_cp_cp_medium != ""){
                                if(utmga4_cp_cp_source != ""){
                                    utmga4_url_arr.push('utm_source='+utmga4_cp_cp_source.replace(" ", "-"));
                                }
                                if(utmga4_cp_cp_medium != ""){
                                    utmga4_url_arr.push('utm_medium='+utmga4_cp_cp_medium.replace(" ", "-"));
                                }
                                if(utmga4_cp_cp_name != ""){
                                    utmga4_url_arr.push('utm_campaign='+utmga4_cp_cp_name.replace(" ", "-"));
                                }
                            }
                            if(utmga4_url_arr.length > 0){
                                if(utmga4_cp_url.indexOf("?") >= 0){
                                    $("#utmga4_of-cp_url").val(utmga4_cp_url+'&'+utmga4_url_arr.join("&"));
                                } else {
                                    $("#utmga4_of-cp_url").val(utmga4_cp_url+'?'+utmga4_url_arr.join("&"));
                                }
                            } else {
                                $("#utmga4_of-cp_url").val("");
                            }
                        }
                    }
                });

                $(document).on("click", "#utmga4_utmga4_of-cp_url", function(e){
                    e.preventDefault();
                    var currObj = $(this);
                    utmga4_copyToClipboard( $("#utmga4_of-cp_url").val() );
                    $("#utmga4_of-url-copied").show();
                    setTimeout(function(){
                        $("#utmga4_of-url-copied").hide();
                    }, 5000);
                });
            });
			
			
        </script>
        <?php }

        public function add_ga4_utm_builder( $atts ){
            ob_start();
            
            $html = $this->inline_css();
            $html .= '<div class="ga4-url-generator">
                
				    <div class="utmga4_of-field">
						<label>What channel are you setting up?</label>
                    <select id="utmga4_cp-channel">
                        <option value="">Select a Channel</option>';

                        //array of channel definitons used to populate helpder text in form
                        $utmga4_channel_definitions = '
                        [
                            {
                                "channel": "Affiliates",
                                "regex_rules": "Medium = affiliate",
                                "source_placeholder": "name or ID of affiliate",
                                "source_rule": "name or ID of affiliate",
                                "medium_placeholder": "Must equal affiliate",
                                "medium_rule": "Must equal <b>affiliate</b>",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "Audio",
                                "regex_rules": "Traffic is DV360 AND DV360 creative format is one of (Audio)",
                                "source_placeholder": "name of audio source (spotify, pandora, etc)",
                                "source_rule": "name of audio source (spotify, pandora, etc)",
                                "medium_placeholder": null,
                                "medium_rule": null,
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "Display",
                                "regex_rules": "Medium is one of (display, banner, expandable, interstitial, cpm)\n<br>OR<br>\nTraffic is Google Ads AND Google Ads ad network type is one of (Google Display Network, Cross-network)",
                                "source_placeholder": "name of display platform",
                                "source_rule": "name of display platform",
                                "medium_placeholder": "Must match one of these mediums: display, banner, expandable, interstitial or cpm",
                                "medium_rule": "Must match one of these mediums: <b>display, banner, expandable, interstitial or cpm</b>",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "Email",
                                "regex_rules": "Source = email|e-mail|e_mail|e mail <br>OR<br> Medium = email|e-mail|e_mail|e mail",
                                "source_placeholder": "name of service (eg: mailchimp, klaviyo, convertkit, etc)",
                                "source_rule": "name of service (eg: mailchimp, klaviyo, convertkit, etc)",
                                "medium_placeholder": "Must equal: email, e-mail, e_mail or e mail",
                                "medium_rule": "Must equal: <b>email, e-mail, e_mail or e mail</b>",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "Mobile Push Notifications",
                                "regex_rules": "Medium ends with push <br>OR<br> Medium contains mobile or notification",
                                "source_placeholder": "name of site pushing notifications",
                                "source_rule": "name of site pushing notifications",
                                "medium_placeholder": "Must end with push or contain mobile or notification",
                                "medium_rule": "Must end with push or contain mobile or notification",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "Organic Search",
                                "regex_rules": "Source matches a list of search sites <br>OR<br> Medium exactly matches organic",
                                "source_placeholder": "name of search engine",
                                "source_rule": "name of search engine",
                                "medium_placeholder": "Must equal organic",
                                "medium_rule": "Must equal <b>organic</b>",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "Organic Shopping",
                                "regex_rules": "Source matches a list of shopping sites <br>OR<br> Campaign name matches regex ^(.*(([^a-df-z]|^)shop|shopping).*)$",
                                "source_placeholder": "name of shopping site (eg: google, bing, etc)",
                                "source_rule": "name of shopping site (eg: google, bing, etc)",
                                "medium_placeholder": "Use organic-shopping",
                                "medium_rule": "Use organic-shopping",
                                "campaign_placeholder": "eg: shopping-spring-sale",
                                "campaign_rule": "Must start with shopping or end with shop preceding a dash like organic-shopping"
                            },
                            {
                                "channel": "Organic Social",
                                "regex_rules": "Source matches a regex list of social sites <br>OR<br> Medium is one of (social, social-network, social-media, sm, social network, social media)",
                                "source_placeholder": "name of social site (facebook, instagram, tiktok, twitter, etc)",
                                "source_rule": "name of social site (facebook, instagram, tiktok, twitter, etc)",
                                "medium_placeholder": "Must equal: social, social-network, social-media, sm, social network or social media",
                                "medium_rule": "Must equal: <b>social, social-network, social-media, sm, social network or social media</b>",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "Organic Video",
                                "regex_rules": "Source matches a list of video sites <br>OR<br> Medium matches regex ^(.*video.*)$",
                                "source_placeholder": "name of video platform (youtube, venmo, etc). It must match a list of known video platforms.",
                                "source_rule": "name of video platform (youtube, venmo, etc). It must match a list of known video platforms.",
                                "medium_placeholder": "Use video",
                                "medium_rule": "Use video",
                                "campaign_placeholder": "eg: Must contain video",
                                "campaign_rule": "Must contain video"
                            },
                            {
                                "channel": "Paid Search",
                                "regex_rules": "Source matches a list of search sites <br>AND<br> Medium matches regex ^(.*cp.*|ppc|paid.*)$",
                                "source_placeholder": "name of search engine (google, bing, baidu, etc)",
                                "source_rule": "name of search engine (google, bing, baidu, etc)",
                                "medium_placeholder": "Must contain cp, equal ppc or start with paid",
                                "medium_rule": "Must contain <b>cp</b>, equal <b>ppc</b> or start with <b>paid</b>",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "Paid Shopping",
                                "regex_rules": "Source matches a list of shopping sites <br>OR<br> Campaign Name matches regex ^(.*(([^a-df-z]|^)shop|shopping).*)$) AND Medium matches regex ^(.*cp.*|ppc|paid.*)$",
                                "source_placeholder": "name of shopping site (google, bing, etc)",
                                "source_rule": "name of shopping site (google, bing, etc)",
                                "medium_placeholder": "Must contain cp, equal ppc or start with paid",
                                "medium_rule": "Must contain <b>cp</b>, equal <b>ppc</b> or start with <b>paid</b>",
                                "campaign_placeholder": "eg: shopping-spring-sale",
                                "campaign_rule": "Must start with shopping or end with shop preceding a - like organic-shoping"
                            },
                            {
                                "channel": "Paid Social",
                                "regex_rules": "Source matches a list of social sites <br>AND<br> Medium matches regex ^(.*cp.*|ppc|paid.*)$",
                                "source_placeholder": "name of social site (facebook, instagram, tiktok, twitter, etc)",
                                "source_rule": "name of social site (facebook, instagram, tiktok, twitter, etc)",
                                "medium_placeholder": "Must contain cp, equal ppc or start with paid",
                                "medium_rule": "Must contain <b>cp</b>, equal <b>ppc</b> or start with <b>paid</b>",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "Paid Video",
                                "regex_rules": "Source matches a list of video sites <br>AND<br> Medium matches regex ^(.*cp.*|ppc|paid.*)$",
                                "source_placeholder": "name of video platform (youtube, venmo, etc). It must match a list of known video platforms.>",
                                "source_rule": "name of video platform (youtube, venmo, etc). It must match a list of known video platforms.",
                                "medium_placeholder": "Must contain cp, equal ppc or start with paid",
                                "medium_rule": "Must contain <b>cp</b>, equal <b>ppc</b> or start with <b>paid</b>",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "Referral",
                                "regex_rules": "medium = referral",
                                "source_placeholder": "name of site sending traffic",
                                "source_rule": "name of site sending traffic",
                                "medium_placeholder": "Must equal referral",
                                "medium_rule": "Must equal <b>referral</b>",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            },
                            {
                                "channel": "SMS",
                                "regex_rules": "Medium exactly matches sms",
                                "source_placeholder": "name of service (postscript, klaviyo, etc)",
                                "source_rule": "name of service (postscript, klaviyo, etc)",
                                "medium_placeholder": "Must equal sms",
                                "medium_rule": "Must equal <b>sms</b>",
                                "campaign_placeholder": "eg: spring-sale",
                                "campaign_rule": "Product, promo code, or slogan (e.g. spring_sale)"
                            }
                        ]
                                ';
                        $utmga4_channels = json_decode($utmga4_channel_definitions);

						// insert values from channels array to populate variables in for fields
                        if(!empty($utmga4_channels)){
                            foreach($utmga4_channels as $utmga4_channel){
                                $html .= '<option 
								    data-regex_rules="'.$utmga4_channel->regex_rules.'"
                                    data-source_placeholder="'.$utmga4_channel->source_placeholder.'"
                                    data-source_rule="'.$utmga4_channel->source_rule.'"
                                    data-medium_placeholder="'.$utmga4_channel->medium_placeholder.'"
                                    data-medium_rule="'.$utmga4_channel->medium_rule.'"
                                    data-campaign_placeholder="'.$utmga4_channel->campaign_placeholder.'"
                                    data-campaign_rule="'.$utmga4_channel->campaign_rule.'"
                                    value="'.$utmga4_channel->channel.'">'.$utmga4_channel->channel.'
                                </option>';
                            }
                        }

                    // create the UTM form
                    $html .= '</select>
                   <span>(only these channels are available in GA4)</span>
                </div>
                <div class="utmga4_of-field">
                    <fieldset>
                        <legend>What\'s the landing page?</legend>
                        <input type="text" id="utmga4_cp-url" value="" placeholder="https://example.com">
                    </fieldset>
                    <span>The full url where you are sending traffic (https://example.com/landing-page/)</span>
                </div>

                <div class="utmga4_of-field source">
                    <fieldset>
                        <legend>Where\'s the traffic coming from? (source)</legend>
                        <input type="text" id="utmga4_cp-cp_source" value="" placeholder="">
                    </fieldset>
                    <span></span>
                </div>
                <div class="utmga4_of-field medium">
                    <fieldset>
                        <legend>How should the traffic be categorized? (medium)</legend>
                        <input type="text" id="utmga4_cp-cp_medium" value="" placeholder="">
                    </fieldset>
                    <span></span>
                </div>
                <div class="utmga4_of-field campaign">
                    <fieldset>
                        <legend>What\'s the campaign name? (campaign)</legend>
                        <input type="text" id="utmga4_cp-cp_name" value="" placeholder="">
                    </fieldset>
                    <span></span>
                </div>

                <div class="utmga4_of-output">
                    <strong>Copy this URL 👇</strong>  <a href="#" id="utmga4_utmga4_of-cp_url">Copy URL</a> 
					<span id="utmga4_of-url-copied" style="display:none;">URL copied to clipboard</span>
                    <textarea id="utmga4_of-cp_url" disabled></textarea>
                </div>
            
               <div> <p><br>These are the rules defined by GA4:</p></div>
                <div class="utmga4_regex_rules">
                    <span></span>
                </div>
                <div id="utmga4_credit">
                    Add <a href="https://ReallyGoodData.com/tool/plugins/ga4-url-builder" target="_blank">GA4 UTM & URL builder</a> to your site</a>
                </div>
            </div>';
            $html .= $this->inline_js();
            $html = apply_filters( 'esc_html', $html );
            echo $html;
            return ob_get_clean();
        }
	}
	new ga4_utm_builder();
}