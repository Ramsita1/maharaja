<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Verify Email</title>
      <style type="text/css">
         body{
         margin:0;
         padding:0;
         }
         @media (max-width:600px){
         table.main_outter {
         width: 100% !important;
         padding: 0 15px !important;
         }  
         body, table{
           width:100%!important;
         } 
         }
      </style>
   </head>
   <body style="width:600px;margin:auto;background: #ececec;" width="600px">
      <?php $header = getThemeOptions('header'); ?>
      <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="600px" id="bodyTable" style="background: #ececec;padding: 20px 0 40px;">
         <tr>
            <td>
               <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="width:600px;margin:auto;">
                  <tr>
                     <td style="text-align:center;padding: 20px 0;">
                        <img src="<?php echo publicPath().'/'.$header['headerlogo'] ?>">
                     </td>
                  </tr>
               </table>
               <table border="0" cellpadding="0" cellspacing="0" width="600px" class="main_outter" style="width:600px;margin:auto;">
                  <tr>
                     <td align="center" valign="top" id="templatePreheader" style="padding:28px 15px;background: #fff;">
                       <h4 style="color: #152c3b;">Welcome To INFIWAY</h4>
                      <p style="color: #152c3b;">Hello <?php echo $name ?>,</p>
                      <p style="color: #152c3b;">You have successfully reset your password.</p>
                      <p style="color: #152c3b;"><b>Email: </b><?php echo $email; ?></p>
                      <p style="color: #152c3b;"><b>Password: </b><?php echo $password; ?></p>
                      <table border="0" cellspacing="0" cellpadding="0">
                         <tr>
                            <td bgcolor="#f3da09" style="padding: 12px 28px 12px 28px; border-radius:3px" align="center"><a href="<?php echo siteUrl(); ?>/login" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #152c3b; text-decoration: none; display: inline-block;">Click to Login</a></td>
                         </tr>
                      </table>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <h5 style="margin-bottom:0;">Need Support ?</h5>
                        <p style="margin-top:8px;">Feel free to email us if you have any questions, comments or suggestions. We'll be happy to resolve your issues.</p>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
   </body>
</html>