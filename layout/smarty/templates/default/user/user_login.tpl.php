<h2><span>Account</span></h2>

{$reason}

<p>If you have an account with us please login below.<br />
&nbsp;</p>
<form class="acc" name="accountlogin" method="post" action="index.php?fuseaction=user.doLogin">

<label for="username">User name:</label>
<input type="text" value="" id="username" name="username" class="text" /><br />

<label for="password">Password:</label>
<input type="password" value="" id="password" name="password" class="text" /> <br />

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" class="btnSubmit" value="Submit" name="submit" /><br><br>
</form>

<p>
Registering with Wickers Gift Baskets allows you to view and edit your billing and delivery addresses.
You can also view your orders.<br>
<div align="left"><a style="cursor:pointer;text-decoration:underline;" class="link_register" onclick="getElementById('tabel_register').style.visibility='visible';">
<b>Please click here to register</b></a></div>
</p>

<br clear="all" />
<table border="0" cellpadding="0" cellspacing="0" id="tabel_register" style="visibility:hidden;">
<tr>
  <td align="left">

<p>&nbsp;</p>
<p>If you would like to open a credit account please complete this form:&nbsp;</p>
<form class="acc" name="accountreg" method="post" action="index.php?fuseaction=user.register2step" onsubmit="return check_submit();">
<input type="hidden" id="no_delivery" name="no_delivery" value="0">

<label for="title">Title:</label>
<select id="title" name="title">
<option value="Mr.">Mr.</option>
<option value="Mrs.">Mrs.</option>
<option value="Ms.">Ms.</option>
</select>
<br clear="all" />

<label for="firstname">First name*:</label>
<input type="text" value="" id="firstname" name="firstname" class="text" /><br />

<label for="surname">Surname*:</label>
<input type="text" value="" id="surname" name="surname" class="text" /> <br />

<label for="email">Email*:</label>
<input type="text" value="" id="email" name="email" class="text" /> <br />

<label for="phone">Telephone:</label>
<input type="text" value="" id="phone" name="phone" class="text" /> <br /><br>

<p style="padding-bottom:0px;">Billing Details</p>
<hr width="100%"  color="#858585"  size="1"/>

<label for="billing_address">Billing Address:</label>
<textarea id="billing_address" name="billing_address" rows="5" cols="20"></textarea><br/>

<label for="billing_postcode">Billing Postcode:</label>
<input type="text" value="" id="billing_postcode" name="billing_postcode" class="text" /> <br />

<label for="billing_country">Billing Country:</label>
<input type="text" value="" id="billing_country" name="billing_country" class="text" /> <br /><br>


<input class="checkbox" type="checkbox" name="deliver_billing" id="deliver_billing" onchange="if(this.checked) {ldelim}
                        document.getElementById('delivery_name').disabled=true;
                        document.getElementById('delivery_address').disabled=true;
                        document.getElementById('delivery_postcode').disabled=true;
                        document.getElementById('delivery_country').disabled=true;
                        document.getElementById('no_delivery').value=1;
                {rdelim} else {ldelim}
                        document.getElementById('delivery_name').disabled=false;
                        document.getElementById('delivery_address').disabled=false;
                        document.getElementById('delivery_postcode').disabled=false;
                        document.getElementById('delivery_country').disabled=false;
                        document.getElementById('no_delivery').value=0;
                {rdelim};">&nbsp;Delivery to billing?<br /><br>



<p style="padding-bottom:0px;">Delivery Details</p>
<hr width="100%" color="#858585"  size="1"  />

<label for="delivery_name">Delivery Name:</label>
<input type="text" value="" id="delivery_name" name="delivery_name" class="text" /> <br />

<label for="delivery_address">Delivery Address:</label>
<textarea id="delivery_address" name="delivery_address" rows="5" cols="20"></textarea><br/>

<label for="delivery_postcode">Delivery Postcode:</label>
<input type="text" value="" id="delivery_postcode" name="delivery_postcode" class="text" /> <br />

<label for="delivery_country">Delivery Country:</label>
<input type="text" value="United Kingdom"  disabled="disabled" id="delivery_country" name="delivery_country" class="text"  /> <br />

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
  <td width="140"></td>
  <td align="left"><input type="submit" class="btnSubmit" value="Register" name="submit" /></td>
</tr>
</table>


</form>

  </td>
</tr>
</table>

<script language='JavaScript'>


function checkemail(str)
{ldelim}

  var emailAddr=str;
  // check for @
  i = emailAddr.indexOf("@");
  if (i == -1)
    return false;

  // separate the user name and domain
  var username = emailAddr.substring(0, i);
  var domain = emailAddr.substring(i + 1, emailAddr.length);

  j=domain.indexOf(".");
  if(j==-1)
    return false;
  else
    {ldelim}
      domain2=domain.substring(0,j);
      domain3=domain.substring(j+1,domain.length);

      if(domain2.length<1)
        return false;

      if(domain3.length<1)
        return false;

    {rdelim}

  // look for spaces at the beginning of the username
  i = 0;
  while ((username.substring(i, i + 1) == " ") &&  (i < username.length))
    i++;

  // remove any found
  if (i > 0)
    username = username.substring(i, username.length);


    // look for spaces at the end of the domain
    i = domain.length - 1;
    while ((domain.substring(i, i + 1) == " ") && (i >= 0))
      i--;

    // remove any found
    if (i < (domain.length - 1))
      domain = domain.substring(0, i + 1);


   // check for bad characters in the username
   var ch;
   for (i = 0; i < username.length; i++)
     {ldelim}
     ch = (username.substring(i, i + 1)).toLowerCase();
     if (!(((ch >= "a") && (ch <= "z")) || ((ch >= "0") && (ch <= "9")) || (ch == "_") || (ch == "-") || (ch == ".")))
            return false;
     {rdelim}


   // check for bad characters in the domain
   for (i = 0; i < domain.length; i++)
   {ldelim}
    ch = (domain.substring(i, i + 1)).toLowerCase();
    if (!(((ch >= "a") && (ch <= "z")) || ((ch >= "0") && (ch <= "9")) || (ch == "_") || (ch == "-") || (ch == ".")))
      return false;
   {rdelim}

   return true;
{rdelim}


function check_submit()
  {ldelim}
   if(!document.forms['accountreg'].firstname.value.length)
     {ldelim}
      alert("Please enter your First Name!");
      document.forms['accountreg'].firstname.focus();
      return false;
     {rdelim}
   else
     if(!document.forms['accountreg'].surname.value.length)
       {ldelim}
        alert("Please enter your Surname!");
        document.forms['accountreg'].surname.focus();
        return false;
       {rdelim}
     else
       if( (!document.forms['accountreg'].email.value.length) || (!checkemail(document.forms['accountreg'].email.value)) )
         {ldelim}
          alert("Please enter a valid e-mail address!");
          document.forms['accountreg'].email.focus();
          return false;
        {rdelim}
       else
	     if(!document.forms['accountreg'].billing_address.value.length)
         {ldelim}
           alert("Please enter your Billing address!");
           document.forms['accountreg'].billing_address.focus();
           return false;
         {rdelim}
		else
	     if(!document.forms['accountreg'].billing_postcode.value.length)
         {ldelim}
           alert("Please enter your Billing postcode!");
           document.forms['accountreg'].billing_postcode.focus();
           return false;
         {rdelim}
else
	     if(!document.forms['accountreg'].billing_country.value.length)
         {ldelim}
           alert("Please enter your Billing country!");
           document.forms['accountreg'].billing_country.focus();
           return false;
         {rdelim}
         else
         return true;
  {rdelim}

</script>



