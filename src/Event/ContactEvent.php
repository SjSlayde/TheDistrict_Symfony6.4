<?php 

namespace App\Event;
use App\Entity\Contact;
use Symfony\Contracts\EventDispatcher\Event;
class ContactEvent extends Event
{
    const TEMPLATE_Contact = "email/contactemail.html.twig";
    private $Contact;
    public function __construct(Contact $Contact){
        $this->Contact = $Contact;
    }

    public function getContact(): Contact{
        return $this->Contact;
    }
}