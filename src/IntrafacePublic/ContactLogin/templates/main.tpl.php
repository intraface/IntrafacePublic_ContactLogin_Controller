<?php
// @todo Get the links working correctly
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="da">
    <head>
        <title><?php e($title); ?></title>
        <meta http-equiv="content-type" content="text/html; charset=<?php e($encoding); ?>" />
        <style type="text/css">
            html {
                font-size: 80%;
            }
            body {
                text-align: center;
                font-family: "trebuchet ms", verdana, sans-serif;
                font-size: 1em;
            }

            #container {
                padding: 1em 2em;
                margin: auto;
                border: 8px solid #ddd;
                width: 90%;
                min-width: 550px;
                text-align: left;
            }
            #content-main {
                width: 65%;
                margin-bottom: -30em;
                padding-bottom: 30em;
                float: left;
            }

            #content-sub {
                width: 30%;
                margin-bottom: -30em;
                padding-bottom: 30em;
                float: right;
            }

            #content {
                clear: both;
            }

            p#logout {
                float: right;
            }
            p#status {
                background: #ddd;
                font-size: 1.5em;
                padding: 0.3em;
                text-align: center;
            }
            p#status span {
                font-size: 0.6em;
                display: block;
                text-align: left;
                padding: 0.3em;
            }
            p.notice, p#notice {
                background: #ccee66;
                padding: 0.8em;
            }

            table {
                width: 100%;
                font-size: 1em;
                margin-bottom: 1em;
            }
            caption {
                background: #ddd;
                padding: 0.5em;
                font-weight: bold;
            }
            th {
                text-align: left;
            }
            #betalingsoversigt td, #prisoversigt td {
                text-align: right;
            }
            a {
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
            }

            #call {
                background: darkblue;
                color: white;
                padding: 0.3em;
            }

            #call span {
                font-size: 1.8em;
                text-align: center;
                display: block;

            }

            span.dankort {
                background: url(/gfx/icons/dankort.jpg) no-repeat right;
                padding-right: 30px;
            }
            @media print {
                #container {
                    margin: 0;
                    padding: 1em;
                    width: 100%;
                }
                .notice {
                    border: 1px solid black;
                    padding: 0.5em;
                }
                #status {
                    border: 1px solid black;
                }
                a {
                    display: none;
                }
                caption {
                    border: 1px solid black;
                }
                #content-main {
                    margin: 0;
                    padding: 0;
                }
                #content-sub {
                    margin: 0;
                    padding: 0;
                }
                #logout {
                    display: none;
                }
                #call {
                    border: 1px solid black;
                }
            }
        </style>

    </head>

    <body>
        <div id="container">
            <div id="content">
    			<?php if ($context->query('flare')): ?>
    			<p class="notice">
    				<?php e($context->query('flare')); ?>
    			</p>
    			<?php endif; ?>
                <?php echo $content; ?>
            </div>

            <p style="clear: both;" />&nbsp;</p>

        </div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-4137665-2");
pageTracker._initData();
pageTracker._trackPageview();
</script>
    </body>

</html>