<?php

	
Class GoogleAction
{
	var $ListIntent="actions.intent.OPTION";    
	var $Listype="type.googleapis.com/google.actions.v2.OptionValueSpec";
	var $Gender="female";
	var $Variant="1";
	var $Pitch="-3st";
	var $Prosody="medium";


	//SimpleResponse TextToSpeech for GoogleAction
	function GoogleTextToSpeech($TextContent)
	{
		$content=implode("\n\n",$TextContent);	
		$simpleResponse=array(
			 "textToSpeech"=>$content,
			// "displayText"=>$content, 
			);
		return $simpleResponse;
	}

	function SimpleGoogleTextToSpeech($TextContent)
	{
        $content=$TextContent;
        $content=
        $simpleResponse=array(
           "textToSpeech"=>"<Speak>".$content."</Speak>",
           "displayText"=>$content, 
          );
        return $simpleResponse;
	}
	
    /***************************************************
    Coverting Each Sentence into SSML Sentences
    ****************************************************/  
    function SSMLSentenceConvertText($TextContent)
    {
        $sentences = preg_split('/(?<=[.?!;:])\s+/', $TextContent, -1, PREG_SPLIT_NO_EMPTY);
        foreach($sentences as $text)
        {
          $content[]="<p><s>".$text."</s></p> <break time=\"0.300\" />";      
        }
        $speechContent=implode("",$content);
        return $speechContent;
    }
  
  
    /***********************************************************************
    Converting Array into SSML Sentences for titles and links
    ************************************************************************/
    function SSMLSentenceConvertArray($TextContent)
    {
    $TextContent=array_unique($TextContent);
    foreach($TextContent as $text)
    {
      $content[]="<p><s>".$text."</s></p> <break time=\"1\" />";      
    }
    $speechContent=implode("",$content);
    return $speechContent;
    }

  
    /***********************************************************************
    Converting Text into Final SSML
    ************************************************************************/
    function GoogleFinalSSML($TextContent)
    {
     

       $fullContentssml="<speak><voice gender='".$this->Gender."' variant='".$this->Variant."'><prosody rate='Medium' pitch='".$this-   >Pitch."'>".$TextContent."</prosody></voice></speak>";
       return $fullContentssml;
    }
  
  
    /***********************************************************************
    Converting Array Text into Display Text
    ************************************************************************/

    function GoogleDisplayText($TextContent)
    {  

       @$displayText=implode("\n\n\n",$TextContent);
       return $displayText;  
    }
  
  
    function GoogleRichResponse($fullContentssml)
    {
      $simpleResponse=array("ssml"=>$fullContentssml,"displayText"=>"");
      $items[]=array("simpleResponse"=>$simpleResponse);
      $richResponse=array("items"=>$items);	
      return $richResponse;
    }

    function GoogleRichResponseDisplayText($fullContentssml,$displayText)
    {
      $simpleResponse=array("ssml"=>$fullContentssml,"displayText"=>$displayText);
      $items[]=array("simpleResponse"=>$simpleResponse);
      $richResponse=array("items"=>$items);	
      return $richResponse;
    }

    function GoogleRichResponseSuggestions($fullContentssml,$displayText,$Suggestions)
    {

      $simpleResponse=array("ssml"=>$fullContentssml,"displayText"=>$displayText);
      $items[]=array("simpleResponse"=>$simpleResponse);
      $richResponse=array("items"=>$items,"suggestions"=>$Suggestions);	
      return $richResponse;
    }

    function GoogleRichResponseBasicSuggestions($fullContentssml,$displayText,$basicCard,$Suggestions)
    {
      $simpleResponse=array("ssml"=>$fullContentssml,"displayText"=>$displayText);
         $items[]=array("simpleResponse"=>$simpleResponse);
         $items[]=array("basicCard"=>$basicCard);
      $richResponse=array("items"=>$items,"suggestions"=>$Suggestions);	
      return $richResponse;
    }
	
    function GoogleSystemIntent($ListTitle,$ListItems)	
    {	
            //$intent="actions.intent.TEXT";
            $intent="actions.intent.OPTION";
            $type="type.googleapis.com/google.actions.v2.OptionValueSpec";

            $listSelect=array("title"=>$ListTitle,"items"=>$ListItems);
            $listData=array("@type"=>$type,"listSelect"=>$listSelect);
            $systemIntent=array("intent"=>$intent,"data"=>$listData);
            return $systemIntent;

    }
 
  
    function GoogleGetPermission($intentName)
    {

    $intent="actions.intent.PERMISSION";
    $type="type.googleapis.com/google.actions.v2.PermissionValueSpec";
    $Data=array("@type"=>$type,"permissions"=>array("UPDATE"),"updatePermissionValueSpec"=>array("intent"=>$intentName));
    $systemIntent=array("intent"=>$intent,"data"=>$Data);
            $google=array(
        'expectUserResponse'=> true,			
        "systemIntent"=>$systemIntent
    );

    //Payload
    $payLoad=array("google"=>$google);
    return $payLoad;

    }
	
    function GooglePayload($ExpectUserResponse,$IsSsml,$NoInputPrompts,$RichResponse,$SystemIntent)
    {

            $google=array(
                'expectUserResponse'=> $ExpectUserResponse,
                 'isSsml'=> $IsSsml,
                 'noInputPrompts'=>$NoInputPrompts,
                 'richResponse' => $RichResponse,
                 "systemIntent"=>$SystemIntent
            );			
            //Payload
            $payLoad=array("google"=>$google);
            return $payLoad;			
    }	



    function GooglePayload_WithoutSystemIntent($ExpectUserResponse,$IsSsml,$NoInputPrompts,$RichResponse)
    {

            $google=array(
                'expectUserResponse'=> $ExpectUserResponse,
                 'isSsml'=> $IsSsml,
                 'noInputPrompts'=>$NoInputPrompts,
                 'richResponse' => $RichResponse
            );

            //Payload
            $payLoad=array("google"=>$google);
            return $payLoad;

    }
  
    function GoogleContext($sessionID,$parameters)
    {
            $sessionName="projects/ReadNews/agent/sessions/".$sessionID."/contexts/sitelist";
            $contexts[]=array("name"=>$sessionName,
                                "lifespanCount"=>30000,
                                "parameters"=>$parameters);
            return $contexts;
    }
  


    //Basic Card for Google Action
    function GoogleBasicCard($Title,$FormattedText,$ButtonUrl,$ButtonTitle)
    {


        $openUrlAction=array("url"=>$ButtonUrl);
        $buttons=array("title"=>$ButtonTitle,"openUrlAction"=>$openUrlAction);
        $basicCard=array(
            "title"=>$Title,
            "formattedText"=>$FormattedText,			
            "buttons"=>array($buttons)
        );
        return $basicCard;

    }
	
 
    function GoogleBasicCardNoButton($Title,$FormattedText)
    {

    //$openUrlAction=array("url"=>$ButtonUrl);
    //$buttons=array("title"=>$ButtonTitle,"openUrlAction"=>$openUrlAction);
    $basicCard=array(
    "title"=>$Title,
    "formattedText"=>$FormattedText,			
    //"buttons"=>array($buttons)
    );
    return $basicCard;

    }
		
     //Basic Card for Google Action
    function GoogleBasicCardWithImage($Title,$FormattedText,$ButtonUrl,$ButtonTitle,$ImageUrl,$ImageText)
    {


        $openUrlAction=array("url"=>$ButtonUrl);
        $buttons=array("title"=>$ButtonTitle,"openUrlAction"=>$openUrlAction);
        $image=array("url"=>$ImageUrl,"accessibilityText"=>$ImageText);
        $basicCard=array(
            "title"=>$Title,
            "formattedText"=>$FormattedText,
            "image"=>$image,
            "buttons"=>array($buttons)
        );
        return $basicCard;

    } 
  	
	
	//Suggestion for GoogleAction
	function GoogleSuggestions($suggestion)
	{
		return array("title"=>$suggestion);
	}
	
		
	function GoogleListOptionInfo($OptionValue)
	{
		return array("key"=>$OptionValue);
	}
	
	function GoogleList($Title,$Description,$OptionInfo)
	{
			return array("optionInfo"=>($this->GoogleListOptionInfo($OptionInfo)),"description"=>$Description,"title"=>$Title);
	}
	


    function GooglePayloadPossibleIntents($ExpectUserResponse,$IsSsml,$NoInputPrompts,$RichResponse)
    {

        $systemIntent=array(
            "intent"=>"actions.intent.SIGN_IN",
            "data"=>array(
                "@type"=> "type.googleapis.com/google.actions.v2.SignInValueSpec"
            )
            );

        $google=array(
            'expectUserResponse'=> $ExpectUserResponse,
            'isSsml'=> $IsSsml,
            'noInputPrompts'=>$NoInputPrompts,
            'richResponse' => $RichResponse,
             'systemIntent'=>$systemIntent				
        );

        //Payload
        $payLoad=array("google"=>$google);
        return $payLoad;

    }	


	function GoogleExpectedInput($NoInputPrompts,$expectedIntent)
	{
  
	}	
	
}

?>
