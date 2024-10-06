/**
 * @package		CGWebp system plugin
 * @author		ConseilGouz
 * @copyright	Copyright (C) 2024 ConseilGouz. All rights reserved.
 * @license		GNU/GPL v3; see LICENSE.php
 * From DJ-WEBP version 1.0.0
 **/
/* handle CGRange field */
document.addEventListener('DOMContentLoaded', function() {
    
    let cgranges = document.querySelectorAll('.form-cgrange');
    for(var i=0; i< cgranges.length; i++) {
        cgranges[i].addEventListener('input',function() {
            label = this.nextElementSibling;
            label.innerHTML = this.value;
        })
    }
    // initialize
    let cglabels = document.querySelectorAll('.cgrange-label');
    for(var i=0; i< cglabels.length; i++) {
        let $id = cglabels[i].getAttribute('data');
        var value = cglabels[i].previousElementSibling.value;
        cglabels[i].innerHTML = value;
    }

    document.addEventListener('joomla:updated',function() {
        // new subform : check for new CGRange field
        let cglabels = document.querySelectorAll('.cgrange-label');
        for(var i=0; i< cglabels.length; i++) {
            let $id = cglabels[i].getAttribute('data');
            var value = cglabels[i].previousElementSibling.value;
            if (!document.querySelector('#'+$id)) {
                // new subform
                let cgranges = document.querySelectorAll('.form-cgrange');
                cgranges[i].addEventListener('input',function() {
                    label = this.nextElementSibling;
                    label.innerHTML = this.value;                
                })
            }
            cglabels[i].innerHTML = value;
        }
    })
})