<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entry;
use AppBundle\Entity\TwitterAPIExchange;
use AppBundle\Entity\Tweet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserController extends Controller
{
    /**
     * @Route("/user/entries/{id}", name="user_entries_list")
     */
    public function tweetAction($id, Request $request)
    {
     
     	$user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);


        $settings = array(
            'oauth_access_token' => "106582776-S9as7vzjeCFtumeYNn84sZTdrCLDBfPfiNRfC1ms",
            'oauth_access_token_secret' => "2DXksBEydgaH7UT6UIZiGuYRcFelAQBLMCj81gOawdkPO",
            'consumer_key' => "ILWouRPfGoQk4CEoODmm9lPM3",
            'consumer_secret' => "nZ8ejZvtOg0vyYyydqwcCuw4fQEtakFLDHlrJRIX4Z6dLuSAqw"
        );

        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name='.$user->getTwitterUsername().'&count=3';
        $requestMethod = 'GET';

        $twitter = new TwitterAPIExchange($settings);
        $object_tweet = $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        $data = json_decode($object_tweet, true);

        $tweets = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($data as $result)
        {
        	$id_tweet = $result['id'];
        	$tweet = $this->getDoctrine()
		            ->getRepository('AppBundle:Tweet')
		            ->find($id_tweet);
        	if(!$tweet)
        	{

            	$tweet = new Tweet();
            	$tweet->setId($id_tweet);
	            $tweet->setUsername($result['user']['screen_name']);
	            $tweet->setHidden(0);
            }

            $tweet->setText($result['text']);
            $tweet->setCreatedAt($result['created_at']);
            $tweets->add($tweet);
        }

        return $this->render('user/tweet.html.twig', array('tweets' => $tweets));
    }

    /**
     * @Route("/tweet/hide/{id}/{username}/{hidden}", name="tweet_hide")
     */
    public function hideAction($id, $username, $hidden, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$tweet = $em->getRepository('AppBundle:Tweet')->find($id);
		$hidden_tweet = $hidden == 0 ? 1 : 0;

        if(!$tweet)
        {
        	$tweet = new Tweet();
        	$username_tweet = $username;

        	$tweet->setId($id);
        	$tweet->setUsername($username_tweet);
        	$tweet->setHidden($hidden_tweet);

        	$em->persist($tweet);
		    $em->flush();
        }
        else
        {
        	$tweet->setHidden($hidden_tweet);
        	$em->flush();

        }
        $response = json_encode(array('id' => $id));

        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));
    }
}