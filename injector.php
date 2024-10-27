<?php

/*
 *  Injects content into page at different places
 */

// Prevent direct access to file
defined('ABSPATH') or die("Cheatin' uh?");

class AdPushup_Injector {

    function __construct() {
        add_action('wp_head', array($this, 'action_wp_head'), 1);
        add_filter('the_content', array($this, 'filter_the_content'));
    }

    public function filter_the_content($content) {

        $pre = '<div id="_ap_wp_content_start" style="display:none"></div>';
        $post = '<div id="_ap_wp_content_end" style="display:none"></div>';
        return $pre . $content . $post;
    }

    /**
     * Runs at the end of <head> tag
     */
    public function action_wp_head() {
        global $wp;
        global $AdPushup;

        $adpushup_site_id = get_option('adpushup_site_id', '');

        $URI = home_url(add_query_arg(array(), $wp->request));
        $site_domain = cleanUrl(get_site_url());
        $plugin_version = $AdPushup->version;
        $page_group = ucwords(getPageType());
        $referer = '';

        if (!empty($_SERVER['HTTP_REFERER'])) {
            $ref_scheme = parse_url($_SERVER['HTTP_REFERER']);
            $site_scheme = parse_url(get_site_url());
            if ($ref_scheme['host'] != $site_scheme['host']) {
                $referer = $ref_scheme['host'];
            }
        }

        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            $no_script_code = json_encode(array("success" => true, 'siteUrl' => cleanUrl(get_site_url()), "urls" => getUrlsForExperiment()), JSON_UNESCAPED_SLASHES);
        } else {
            $no_script_code = str_replace("\\/", "/", json_encode(array("success" => true, 'siteUrl' => cleanUrl(get_site_url()), "urls" => getUrlsForExperiment())));
        }


//                      AdPushup noscript code starts (Required for call by server)
        echo '
<!-- AdPushup Begins -->		
<noscript>
    _ap_ufes' . $no_script_code . '_ap_ufee
</noscript>
';
//                      AdPushup noscript code ends
//                      AdPushup script code starts
        echo '		
<script data-cfasync="false" type="text/javascript">
	(function(w,d){(w.adpushup=w.adpushup||{}).configure={config:{e3Called:false,jqLoaded:0,apLoaded:0,e3Loaded:0,rand:Math.random()}};var adp=w.adpushup,json=null,config=adp.configure.config,tL=adp.timeline={},apjQuery=null;tL.tl_adpStart=+new Date;adp.utils={uniqueId:function(appendMe){var d=+new Date,r,appendMe=((!appendMe||(typeof appendMe=="number"&&appendMe<0))?Number(1).toString(16):Number(appendMe).toString(16));appendMe=("0000000".substr(0,8-appendMe.length)+appendMe).toUpperCase();return appendMe+"-xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g,function(c){r=((d=Math.floor(d / 16))+Math.random()*16)%16|0;return(c=="x"?r:(r&0x3|0x8)).toString(16);});},loadScript:function(src,sC,fC){var s=d.createElement("script");s.src=src;s.type="text/javascript";s.async=true;s.onerror=function(){if(typeof fC=="function"){fC.call();}};if(typeof d.attachEvent==="object"){s.onreadystatechange=function(){(s.readyState=="loaded"||s.readyState=="complete")?(s.onreadystatechange=null&&(typeof sC=="function"?sC.call():null)):null};}else{s.onload=function(){(typeof sC=="function"?sC.call():null)};} (d.getElementsByTagName("head")[0]||d.getElementsByTagName("body")[0]).appendChild(s);}};adp.configure.push=function(obj){for(var key in obj){this.config[key]=obj[key];} if(!this.config.e3Called&&this.config.siteId&&this.config.pageGroup&&this.config.packetId){var c=this.config,ts=+new Date;adp.utils.loadScript("//e3.adpushup.com/E3WebService/e3?ver=2&callback=e3Callback&siteId=' . esc_js($adpushup_site_id) . '&url="+encodeURIComponent(c.pageUrl)+"&pageGroup="+c.pageGroup+"&referrer="+encodeURIComponent(d.referrer)+"&cms="+c.cms+"&pluginVer="+c.pluginVer+"&rand="+c.rand+"&packetId="+c.packetId+"&_="+ts);c.e3Called=true;tL.tl_e3Requested=ts;init();} adp.ap&&typeof adp.ap.configure=="function"&&adp.ap.configure(obj);};function init(){(w.jQuery&&w.jQuery.fn.jquery.match(/^1.11./))&&!config.jqLoaded&&(tL.tl_jqLoaded=+new Date)&&(config.jqLoaded=1)&&(apjQuery=w.jQuery.noConflict(true));(typeof adp.runAp=="function")&&!config.apLoaded&&(tL.tl_apLoaded=+new Date)&&(config.apLoaded=1);if(!adp.configure.config.apRun&&adp.configure.config.pageGroup&&apjQuery&&typeof adp.runAp=="function"){adp.runAp(apjQuery);adp.configure.push({apRun:true});} if(!adp.configure.config.e3Run&&w.apjQuery&&typeof adp.ap!="undefined"&&typeof adp.ap.triggerAdpushup=="function"&&json&&typeof json!="undefined"){adp.ap.triggerAdpushup(json);adp.configure.push({e3Run:true});}};w.e3Callback=function(){(arguments[0])&&!config.e3Loaded&&(tL.tl_e3Loaded=+new Date)&&(config.e3Loaded=1);json=arguments[0];init();};adp.utils.loadScript("//optimize.adpushup.com/' . esc_js($adpushup_site_id) . '/apv2.js",init);tL.tl_apRequested=+new Date;adp.utils.loadScript("//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js",init);tL.tl_jqRequested=+new Date;adp.configure.push({siteId:"' . esc_js($adpushup_site_id) . '",packetId:adp.utils.uniqueId(' . esc_js($adpushup_site_id) . '),pageGroup:"' . $page_group . '",siteDomain:"' . $site_domain . '",pageUrl:"' . esc_js($URI) . '",referrer:"' . esc_js($referer) . '",cms:"wordpress",pluginVer:"' . $plugin_version . '"});})(window,document);
</script>
<!-- AdPushup Ends -->
';
//                      AdPushup script code ends
    }

}

new AdPushup_Injector();
