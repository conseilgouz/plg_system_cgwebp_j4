/**
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2025 ConseilGouz. All rights reserved.
 * @license		GNU/GPL v3; see LICENSE.php
 * From DJ-WEBP version 1.0.0
 **/

document.addEventListener("DOMContentLoaded", function(){
    
    // Destroy button
    deletewebp = document.querySelector('#webpdestroy');
    if (!deletewebp) return; // not defined : ignore
    deletewebp.addEventListener('click',function() {
        delete_webp();
    })
    // check CG custom classes
    clears = document.querySelectorAll('.view-plugin .clear');
    for(var i=0; i< clears.length; i++) {
        let clear = clears[i];
        group = clear.parentNode.parentNode.parentNode.style.clear = "both";
    }
    lefts = document.querySelectorAll('.view-plugin .left');
    for(var i=0; i< lefts.length; i++) {
        let left = lefts[i];
        group = left.parentNode.parentNode.parentNode.style.float = "left";
    }
    halfs = document.querySelectorAll('.view-plugin .half');
    for(var i=0; i< halfs.length; i++) {
        let half = halfs[i];
        group = half.parentNode.parentNode.parentNode.style.width = "50%";
    }
    halfs = document.querySelectorAll('.view-plugin .sixty');
    for(var i=0; i< halfs.length; i++) {
        let half = halfs[i];
        group = half.parentNode.parentNode.parentNode.style.width = "60%";
    }
    
    // show/hide button depending on Storage value
    storageMedia = document.querySelector('#jform_params_storage1');
    storageSame = document.querySelector('#jform_params_storage0');
    
    if (!storageMedia || !storageSame) return; // check existing buttons 
    
    if (storageSame.getAttribute('checked')) {
        deletewebp.classList.add('hidden');
    }
    storageMedia.addEventListener('change',function() {
            deletewebp.classList.remove('hidden');
   })
    storageSame.addEventListener('change',function() {
        deletewebp.classList.add('hidden');
   })
})    
function delete_webp() {    
    let deletewebp = document.querySelector('#webpdestroy');
    deletewebp.setAttribute("disabled",true);
    let box = document.createElement('div');
    let systemmsg = document.querySelector('#destroy_message');
    box.innerHTML = '<joomla-alert type="warning" role="alert" style="animation-name: joomla-alert-fade-in;"><div class="alert-heading"><span class="visually-hidden">info</span></div><div class="alert-wrapper"><div class="alert-message"><p>Cleaning WEBP images....</p><p style="text-align: center;margin-left: 10em;"><span class="switching"></span></div></div></joomla-alert>';
    systemmsg.appendChild(box);
	var csrf = Joomla.getOptions("csrf.token", "");
	var url = "?"+csrf+"=1&option=com_ajax&plugin=cgwebp&task=clean&format=raw";
	Joomla.request({
		method : 'POST',
		url : url,
		onSuccess: function(data, xhr) {
            if (data != 'ok') {
                window.alert(data);
            }
            deletewebp.removeAttribute("disabled");
            systemmsg.removeChild(box);
		},
		onError: function(message) {
            console.log(message.responseText);
            deletewebp.removeAttribute("disabled");
            systemmsg.removeChild(box);
        }
	}) 
}
