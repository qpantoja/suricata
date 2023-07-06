

function Validator(frmname)
{
  this.formobj=document.forms[frmname];
	if(!this.formobj)
	{
	  alert("BUG: couldnot get Form object "+frmname);
		return;
	}
	if(this.formobj.onsubmit)
	{
	 this.formobj.old_onsubmit = this.formobj.onsubmit;
	 this.formobj.onsubmit=null;
	}
	else
	{
	 this.formobj.old_onsubmit = null;
	}
	this.formobj.onsubmit=form_submit_handler;
	this.addValidation = add_validation;
	this.setAddnlValidationFunction=set_addnl_vfunction;
	this.clearAllValidations = clear_all_validations;
}


function set_addnl_vfunction(functionname)
{
  this.formobj.addnlvalidation = functionname;
}


function clear_all_validations()
{
	for(var itr=0;itr < this.formobj.elements.length;itr++)
	{
		this.formobj.elements[itr].validationset = null;
	}
}


function form_submit_handler()
{
	for(var itr=0;itr < this.elements.length;itr++)
	{
		if(this.elements[itr].validationset &&
	   !this.elements[itr].validationset.validate())
		{
		  return false;
		}
	}
	if(this.addnlvalidation)
	{
	  str =" var ret = "+this.addnlvalidation+"()";
	  eval(str);
    if(!ret) return ret;
	}
	return true;
}


function add_validation(itemname,descriptor,errstr)
{
  if(!this.formobj)
	{
	  alert("BUG: the form object is not set properly");
		return;
	}
	var itemobj = this.formobj[itemname];
  if(!itemobj)
	{
	  alert("BUG: Couldnot get the input object named: "+itemname);
		return;
	}
  if(!itemobj.validationset)
	{
	  itemobj.validationset = new ValidationSet(itemobj);
	}
  itemobj.validationset.add(descriptor,errstr);
}


function ValidationDesc(inputitem,desc,error)
{
  this.desc=desc;
  this.error=error;
  this.itemobj = inputitem;
  this.validate=vdesc_validate;
}


function vdesc_validate()
{
 if(!V2validateData(this.desc,this.itemobj,this.error))
 {
   this.itemobj.focus();
   return false;
 }
 return true;
}


function ValidationSet(inputitem)
{
    this.vSet=new Array();
	this.add= add_validationdesc;
	this.validate= vset_validate;
	this.itemobj = inputitem;
}


function add_validationdesc(desc,error)
{
  this.vSet[this.vSet.length]= 
	  new ValidationDesc(this.itemobj,desc,error);
}


function vset_validate()
{
   for(var itr=0;itr<this.vSet.length;itr++)
	 {
	   if(!this.vSet[itr].validate())
		 {
		   return false;
		 }
	 }
	 return true;
}


function validateEmailv2(email)
{
    if(email.length <= 0)
	{
	  return true;
	}
    var splitted = email.match("^(.+)@(.+)$");
    if(splitted == null) return false;
    if(splitted[1] != null )
    {
      var regexp_user=/^\"?[\w-_\.]*\"?$/;
      if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null)
    {
      var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
      if(splitted[2].match(regexp_domain) == null) 
      {
	    var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
	    if(splitted[2].match(regexp_ip) == null) return false;
      }// if
      return true;
    }
return false;
}


function V2validateData(strValidateStr,objValue,strError) 
{ 
    var epos = strValidateStr.search("="); 
    var  command  = ""; 
    var  cmdvalue = ""; 
    if(epos >= 0) 
    { 
     command  = strValidateStr.substring(0,epos); 
     cmdvalue = strValidateStr.substr(epos+1); 
    } 
    else 
    { 
     command = strValidateStr; 
    }

    switch(command) 
    { 
        case "req": 
        case "required": 
         { 
           if(eval(objValue.value.length) == 0) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : Required Field"; 
              }
              alert(strError); 
              return false; 
           }
           break;             
         }

         //Date validation...
         case "date":
				var validformat=/^\d{4}\-\d{2}\-\d{2}$/ //Basic check for format validity
				if (!validformat.test(objValue.value))
				{
					alert(strError);
					return false;
				}
				else
				{
					//Detailed check for valid date ranges
					var dayfield=objValue.value.split("-")[2];
					var monthfield=objValue.value.split("-")[1];
					var yearfield=objValue.value.split("-")[0];
					var dayobj = new Date(yearfield, monthfield-1, dayfield)
					if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield))
					{
						alert(strError);
						return false;
					}
				}
         	break;
         
         
         
         case "password": 
         	var frmpass = document.forms["changepasswd"];
         	if(frmpass.New_Password.value != frmpass.Confirmation.value)
         	{
         		alert(strError);
         		return false;
         	}
         	break;

        case "maxlength": 
        case "maxlen": 
          { 
             if(eval(objValue.value.length) >  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : "+cmdvalue+" characters maximum "; 
               }
               alert(strError + "\n[Current length = " + objValue.value.length + " ]"); 
               return false; 
             }
             break; 
          }


        case "minlength": 
        case "minlen": 
           { 
             if(eval(objValue.value.length) <  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : " + cmdvalue + " characters minimum  "; 
               }
               alert(strError + "\n[Current length = " + objValue.value.length + " ]"); 
               return false;                 
             }
             break; 
            }


        case "alnum": 
        case "alphanumeric": 
           { 
              var charpos = objValue.value.search(/[^A-Za-záéíóú,0-9\s\.%\$]/); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
               if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alpha-numeric characters allowed "; 
                }//if 
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }


        case "num": 
        case "numeric": 
           { 
              var charpos = objValue.value.search("[^0-9]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only digits allowed "; 
                }
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }
              break;               
           }

        case "alphabetic":
        case "alpha": 
           { 
              //var charpos = objValue.value.search("[^A-Za-z]");
              var charpos = objValue.value.search(/[^A-Za-záéíóú,\.\s%\$]/);
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alphabetic characters allowed "; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }
              break; 
           }

	case "alnumhyphen":
	{
              var charpos = objValue.value.search("[^A-Za-z0-9\-_\b]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,- and _"; 
                }
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }
               break;
	}


        case "email": 
          { 
               if(!validateEmailv2(objValue.value)) 
               { 
                 if(!strError || strError.length ==0) 
                 { 
                    strError = objValue.name+": Enter a valid Email address "; 
                 }//if                                               
                 alert(strError); 
                 return false; 
               }
           break; 
          }


        case "lt": 
        case "lessthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }
            if(eval(objValue.value) >=  eval(cmdvalue)) 
            { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : value should be less than "+ cmdvalue; 
              }           
              alert(strError); 
              return false;                 
             }
            break; 
         }


        case "gt":
        case "greaterthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }
             if(eval(objValue.value) <=  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : value should be greater than "+ cmdvalue; 
               }    
               alert(strError); 
               return false;                 
             }
            break; 
         }


        case "regexp": 
         { 
		if(objValue.value.length > 0)
		{
	            if(!objValue.value.match(cmdvalue)) 
	            { 
	              if(!strError || strError.length ==0) 
	              { 
	                strError = objValue.name+": Invalid characters found "; 
	              }
	              alert(strError); 
	              return false;                   
	            }
		}
           break; 
         }

        case "dontselect": 
         { 
            if(objValue.selectedIndex == null) 
            { 
              alert("BUG: dontselect command for non-select Item"); 
              return false; 
            } 
            if(objValue.selectedIndex == eval(cmdvalue)) 
            { 
             if(!strError || strError.length ==0) 
              { 
              strError = objValue.name+": Please Select one option "; 
              }
              alert(strError); 
              return false;                                   
             }
             break; 
         }
         //******************** agregado pa comparar fechas
         case "datecomp":
         {
         	var dayfield=objValue.value.split("-")[2];
			var monthfield=objValue.value.split("-")[1];
			var yearfield=objValue.value.split("-")[0];
			var date1  = new Date(yearfield,monthfield-1,dayfield);
         	
			var dayfield=document.forms[0][cmdvalue].value.split("-")[2];
			var monthfield=document.forms[0][cmdvalue].value.split("-")[1];
			var yearfield=document.forms[0][cmdvalue].value.split("-")[0];
			var date2  = new Date(yearfield,monthfield-1,dayfield);
			
			if (date1 > date2)
			{
				alert (strError);
				return false;
			}
         }
         break;
         //******************** agregado pa comparar fechas
    }
    return true; 
}

//this part is for confirmations for example when 
//a project delete is requested
function confirmation(message,link)
{
	var answer = confirm(message)
	if (answer){
		window.location = link;
	}
}