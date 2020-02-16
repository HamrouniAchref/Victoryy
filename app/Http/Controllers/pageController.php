<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

  

class pageController extends Controller
{
    function image(Request $request){
        //dd($request->image);
        $img = fopen(public_path('aze.jpg'), 'r');
        $client = new Client();
        $url = 'zupimages.net/up/20/07/rn5r.jpg' ;
        $url2='i1.rgstatic.net/ii/profile.image/474649009102850-1490176550798_Q512/Mohamed_Hmiden2.jpg' ;
        $url3='zupimages.net/up/20/07/pju4.jpg';
        $url1=$request->image ;
       $response = $client->request('POST','https://apis.paralleldots.com/v3/facial_emotion?url=https://www.zupimages.net/up/20/07/'.$url1.'&api_key=w1N8Kwo7duxReYE6oIbYm7Ds0OMrKxwOvfRB8Gy8Pf0', ['verify' => false],
       [ 'body' => [  
            'file'    => $img,
           
       ]]);
        
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $tab=json_decode($response->getBody(), true);
        //return $body;
        //dd($tab['facial_emotion']);
        //dd($tab['facial_emotion'][0]["score"]);
        $max=$tab['facial_emotion'][0] ; 
        for($i=1;$i<sizeof($tab['facial_emotion']);$i++){
            if($tab['facial_emotion'][$i]["score"]>$max["score"]) 
            $max=$tab['facial_emotion'][$i] ;
        }
        
    // $score=   $max['score'];
    
        $type='success';
       $msg1=$max['tag'] ; 
       $msg ='Monsieur vous êtes '.$msg1 ; 
        
        
        session()->flash('message', $msg);
        session()->flash('type', $type);
        return redirect()->route('index');
        

    }
    function homee(Request $request){
        
        $text = $request->message;
        $client = new Client();
        $response = $client->request('POST','https://apis.paralleldots.com/v3/sentiment?text='.$text.'&api_key=w1N8Kwo7duxReYE6oIbYm7Ds0OMrKxwOvfRB8Gy8Pf0', ['verify' => false],);
  
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $tab=json_decode($response->getBody(), true);
        //return $body;
    
        $scoreN=$tab['probabilities']['negative']*100;
        $scoreNo=$tab['probabilities']['neutral']*100;
        $scoreP=$tab['probabilities']['positive']*100;
   //     dd($score);
   if($scoreN<20)
   {
       $type='success';
       $msg="Monsieur vous n'êtes pas stressé, votre score est ".$scoreN. " vous pouver  continuer votre travail  ";
       
   }else
        if($scoreN>55)
        {
            $type='danger';
            $msg="votre score est de " .$scoreN. " Monsieur vous êtes stressé pensez à prendre une pose de 30 minutes   ";
          
            
        }else {
            $type='danger';
            $msg="Monsieur vous êtes un peu stressé, votre score est ".$scoreN. " pensez à prendre une pose 15min  ";
        }
        
        
        session()->flash('message', $msg);
        session()->flash('type', $type);
        return redirect()->route('index');
        

    }
    function index(){
      
        return view('index');
        

    }

  
}
