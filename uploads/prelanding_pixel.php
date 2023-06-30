<?php
    // Check Facebook Pixel
    if (isset($_GET['fb']) && $_GET['fb'] != '') {
?>

    <!-- Start Facebook Pixel Code -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function() {
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '<?=$_GET['fb'];?>');
                setTimeout(function() {fbq('track', 'PageView'); }, 20000);
            }, 3000);
        });
    </script>
    <noscript><img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id=<?=$_GET['fb'];?>&ev=PageView&noscript=1' /></noscript>
    <!-- End Facebook Pixel Code -->

<?php
    }
    if (isset($_GET['mtrk']) && $_GET['mtrk'] != '') {
?>

    <!-- Start Yandex.Metrika counter -->
    <!--<script type="text/javascript" >
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function() {
                (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
                (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

                ym(<?=$_GET['mtrk'];?>, "init", {
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                });
            }, 2000);
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/<?=$_GET['mtrk'];?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>-->
    <!-- End Yandex.Metrika counter -->

<?php
    }
    if (isset($_GET['imotech']) && $_GET['imotech'] != '') {
?>
        
    <!-- Start Imotech Pixel -->
    <script>
        window.bgdataLayer = window.bgdataLayer || [];
        function bge(){bgdataLayer.push(arguments);}
        bge('init', "<?=$_GET['imotech'];?>");
    </script>
    <script async src="https://api.imotech.video/ad/events.js?pixel_id=<?=$_GET['imotech'];?>"></script>
    <!-- End Imotech Pixel -->
        
<?php
    }
?>