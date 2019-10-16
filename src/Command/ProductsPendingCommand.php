<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProductsPendingCommand extends Command
{
    protected static $defaultName = 'products:pending';

    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, string $name = null)
    {
        parent::__construct($name);

        $this->em     = $em;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Returns products on "pending" for a week or more')
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time = '1 week'; //may be obtained from $input
        $body = '';

        $repository = $this->em->getRepository(Product::class);

        if ($products = $repository->findAllPending($time)) {
            foreach ($products as $product) {
                $body .= $product->getIssn() . "\r\n";
                $body .= $product->getName() . "\r\n";
                $body .= $product->getCustomer()->getUuid() . "\r\n";
                $body .= $product->getUpdatedAt()->format('Y-m-d H:i:s') . "\r\n";
                $body .= "\r\n";
            }

            $message = (new \Swift_Message("The products on \"pending\" for $time or more"))
                ->setFrom('send@example.com')
                ->setTo('recipient@example.com')
                ->setBody($body, 'text/plain');

            $this->mailer->send($message);
        }

    }
}
