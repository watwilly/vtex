<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Http\Controllers\ProccessController as proccess;
use \App\Models\Ordenes as order;


class vtex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migracion de datos via API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        $uri = "https://knownonline.vtexcommercestable.com.br/api/oms/pvt/orders?&f_creationDate=creationDate%3A%5B2021-01-01T02%3A00%3A00.000Z%20TO%202021-10-29T01%3A59%3A59.999Z%5D&f_hasInputInvoice=false";
        $getAllorder = proccess::getOrder($uri);

        $allOrder = json_decode($getAllorder);

        foreach ($allOrder->list as  $order) {

            if ($order->status == "ready-for-handling") {
                $checkOrder = order::select("id","orderId")->where("orderId", $order->orderId)->first();

                if (is_null($checkOrder)) {
                    $dataNewOrder = [
                        "orderId"=>$order->orderId,
                        "total_amount"=>$order->totalValue,
                        "payment_method"=>$order->paymentNames
                    ];

                    $createOrder = order::create($dataNewOrder);
                   
                    $uriOrder = "https://knownonline.vtexcommercestable.com.br/api/oms/pvt/orders/".$order->orderId;
                    $GetOrder = proccess::getOrder($uriOrder);

                    $GetaOrder = json_decode($GetOrder);
                    $GetCliente = $GetaOrder->clientProfileData;
                    //dd($GetCliente->lastName);
                    $dataCliente = [
                        'nombre'=>$GetCliente->firstName,
                        'apellido'=>$GetCliente->lastName,
                        'email'=>$GetCliente->email,
                    ];

                    $createOrder->clientesId()->create($dataCliente);

                    if ($GetaOrder){
                        foreach ($GetaOrder->items as  $item){

                            $itemData = [
                                'refId'=>$item->refId,
                                'productId'=>$item->productId,
                                'name'=>$item->name,
                                'quantity'=>$item->quantity,
                            ];
                             
                            $createOrder->itemsId()->create($itemData);

                        }
                    }
                }else{
                    echo "Esta Orden ".$order->orderId." esta ingresada";
                }
            }

        }
    }
}
