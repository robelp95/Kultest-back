<?php
namespace App\Command;

use App\Entity\PaykuPlan;
use App\Service\Payku\PaykuCreatePlan;
use App\Service\Payku\PaykuGetPlans;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePlansCommand extends Command {

    protected static $defaultName = 'app:payku:create-plans';

    private EntityManagerInterface $em;
    private PaykuGetPlans $paykuGetPlans;

    public function __construct(
        EntityManagerInterface $em,
        PaykuGetPlans $paykuGetPlans
    ) {
        $this->em = $em;
        $this->paykuGetPlans = $paykuGetPlans;
        parent::__construct();
    }

    protected function configure() {
        $this->setDescription('Creates the yearly plan');
        $this->setHelp("This command lets you create a yearly plan in your Payku Account");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        try{
            $plans = ($this->paykuGetPlans)();
            $planArray = array_filter($plans["plans"], function ($elem){
                return $elem["status"] == "active";
            }) ;
            foreach ($planArray as $item){
                $plan = new PaykuPlan();
                $plan->setName($item["name"]);
                $plan->setPaykuId($item["id"]);
                $this->em->persist($plan);
            }

        } catch (\Exception $e) {
//            print_r($e->getMessage() . ' ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
        $this->em->flush();
        return Command::SUCCESS;
    }

}