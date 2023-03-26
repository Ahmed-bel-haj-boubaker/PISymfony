<?php

namespace App\Controller;
use App\ChatBot\Conversation\OnBoardingConversation;
use App\ChatBot\Conversation\QuestionConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\SymfonyCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\Drivers\Web\WebDriver;
use ReceiveMiddleware;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Entity\User;
use BotMan\BotMan\Interfaces\UserInterface as InterfacesUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ChatBotController extends AbstractController
{
    #[Route('/tunibot', name: 'tunibot')]
    public function chatAction()
{
    DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

    // Configuration for the BotMan WebDriver
    $config = [];

    // Create BotMan instance
    $botman = BotManFactory::create($config);

    // Give the bot some things to listen for.
    $botman->hears(
        '/gif {gif}',
        function (BotMan $bot, string $gif) {
            $bot->reply(
                OutgoingMessage::create('this is your gif')
                    ->withAttachment($this->fetchGiphyGif($gif))
            );
        }
    );
    $botman->hears(
        'weather in {location}',
        function (BotMan $bot, string $location) {
            $response = $this->fetchWeatherData($location);
            $bot->reply(sprintf('<img src="%s" alt="icon"/>', $response->current->weather_icons[0]));
            $bot->reply(sprintf('Weather in %s is %s!', $response->location->name, $response->current->weather_descriptions[0]));
        }
    );
    $botman->hears(
        'je m appelle {name}',
        function (BotMan $bot, string $name) {
            $emojie = "\u{1F60D}";
            $bot->userStorage()->save(['name' => $name]);
            $bot->reply('je suis ravi de vous connaître, ' . $name.$emojie);
            $bot->reply('vous etes de quelles region '. $name.' ?');
        }
    );
    $botman->hears(
        'j habite a {location}',
        function (BotMan $bot, string $location) {
            $bot->userStorage()->save(['location' => $location]);
            $bot->reply('oui je la connait bien ' . $location.'');
            $bot->reply(' et quelle age as tu ? ');
        }
    );
    $botman->hears(
        'je suis {age} ans',
        function (BotMan $bot ,int $age) {
            if($age<18){
                $emojie = "\u{1F602}";
                $bot->reply('tu es encore petit '. $bot->userStorage()->get('name').' il faut que tu remporte ton pére au stade hhhh'.$emojie);
            }else if($age>18){
                $emojie = "\u{1F497}";
                $bot->reply(' Super! etes-vous excité a propos nos evenement '. $bot->userStorage()->get('name').'? '.$emojie);

            }



        }
    );
    $botman->hears(
        'oui bien sure',
        function (BotMan $bot) {
            $emojie = "\u{1F497}";
            $bot->reply('Bienvenue donc dans notre site web il y a beaucoup de service que vous pouvez les consulter');
              $bot->reply('vous pouvez reserver un match dans notre ligue 1 ');
              $bot->reply('et le paiement est en ligne et aussi nous avons des evenement extraordinaire');
             $bot->reply('accrocher vos place !!'.$emojie);
        }
    );

    $botman->hears(
        'bonjour',
        function (BotMan $bot) {
            $emojie = "\u{1F497}";
            $bot->reply('salut mon amis, c est quoi votre nom ?'.$emojie);
        }
    );
    $botman->hears(
        'information',
        function (BotMan $bot) {
            $bot->reply('tu es ' . $bot->userStorage()->get('name').' '.$bot->userStorage()->get('age').' , '.'  et tu habite a '.$bot->userStorage()->get('location'));
        }
    );
    // $botman->hears(
    //     'information',
    //     function (BotMan $bot) {
    //         $user = $bot->getUser();
    //         $bot->reply('First name: ' . $user->getFirstName());
    //     }
    // );

    // Set a fallback
    $botman->fallback(function (BotMan $bot) {
        $bot->reply('Sorry, I did not understand.');
    });

    // Start listening
    $botman->listen();

    return new Response();
 }
 #[Route('/chat', name: 'chat')]
 public function index(): Response
 {
     return $this->render('chatIndex.html.twig');
 }


    #[Route('/chatframe', name: 'chatframe')]
    public function chatframeAction(Request $request)
    {
        return $this->render('tunibot/index.html.twig');
    }
    private function fetchWeatherData(string $location): stdClass
    {
        //😀 dirty, but simple and fine for me in POC
        $url = 'http://api.weatherstack.com/current?access_key=495e91f5482b29842f441007f3981fb9&query=' . urlencode($location);



        return json_decode(file_get_contents($url));
    }

    private function fetchGiphyGif(string $name): Image
    {
        $url = sprintf('https://api.giphy.com/v1/gifs/H2meCvoDg0tGUoawemeXEjPEveKv3fzs?api_key=H2meCvoDg0tGUoawemeXEjPEveKv3fzs', urlencode($name));
        $response = json_decode(file_get_contents($url));

        return new Image($response->data[0]->images->downsized_large->url);
    }
}