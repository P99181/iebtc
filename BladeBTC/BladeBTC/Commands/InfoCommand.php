<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\BotSetting;
use BladeBTC\Models\InvestmentPlan;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class InfoCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "info";

    /**
     * @var string Command Description
     */
    protected $description = "Info menu.";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {

        /**
         * Chat data
         */
        $id = $this->update->getMessage()->getFrom()->getId();


        /**
         * Display Typing...
         */
        $this->replyWithChatAction([ 'action' => Actions::TYPING ]);


        /**
         * Verify user
         */
        $user = new Users($id);
        if ($user->exist() == false) {

            $this->triggerCommand('start');

        }
        else {

            /**
             * Keyboard
             */
            $keyboard = [
                [ "My balance " . Btc::Format($user->getBalance()) . " \xF0\x9F\x92\xB0" ],
                [ "Invest \xF0\x9F\x92\xB5", "Withdraw \xE2\x8C\x9B" ],
                [ "Reinvest \xE2\x86\xA9", "Help \xE2\x9D\x93" ],
                [ "My Team \xF0\x9F\x91\xAB" ],
            ];

            $reply_markup = $this->telegram->replyKeyboardMarkup([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
            ]);

            /**
             * Response
             */


            $this->replyWithMessage([
                'text' => "<b>First steps</b>🎽

Your perfect start.

✏️ Prima di tutto devi avere un wallet e dei fondi nel wallet (minimum invest is " . InvestmentPlan::getValueByName("minimum_invest") . " BTC).

✏️ " . BotSetting::getValueByName("app_name") . " offre a te e ai tuoi partner " . InvestmentPlan::getValueByName("base_rate") . "% al giorno, per " . InvestmentPlan::getValueByName("contract_day") . " giorni. Riceverai  " . (InvestmentPlan::getValueByName("base_rate") / (24 / InvestmentPlan::getValueByName("timer_time_hour"))) . "% ogni " . InvestmentPlan::getValueByName("timer_time_hour") .  "ora, dal momento del deposito fino al " . InvestmentPlan::getValueByName("contract_day") . " days are over. The minimum invest is " . InvestmentPlan::getValueByName("minimum_invest") . " BTC and the minimum for a withdraw is " . InvestmentPlan::getValueByName("minimum_payout") . " BTC. You can invest as many times as you want and I also offer you to reinvest your balance, the minimum for reinvest is " . InvestmentPlan::getValueByName("minimum_reinvest") . " BTC.

<b>Deposit - Invest</b> 💵

✏️ Premi il pulsante investimento per fare il tuo primo investimento

✏️Ora vedi un lungo ID portafoglio con numeri e lettere. Copia questo indirizzo e invia l'importo che desideri investire dal tuo portafoglio al portafoglio visualizzato all'interno di " . BotSetting::getValueByName("app_name") . ".

✏️ Puoi verificarlo sempre sul \"My balance\" button. Troverai anche tutte le informazioni su quanti giorni rimangono nei tuoi attuali investimenti. A volte gli investimenti o i prelievi possono richiedere più di tempo, ma questo non dipende da me, poiché è controllato da Blockchain.

✏️ E da ora in poi guadagni " . InvestmentPlan::getValueByName("base_rate") . "% giornalmente, " . (InvestmentPlan::getValueByName("base_rate") / (24 / InvestmentPlan::getValueByName("timer_time_hour"))) . "% sempre " . InvestmentPlan::getValueByName("timer_time_hour") . " hours until the " . InvestmentPlan::getValueByName("contract_day") . " days are over.

✏️ Spetta a te se desideri investire nuovamente il tuo saldo attuale o se desideri prelevarlo. Se vuoi reinvestire il tuo saldo attuale, premi semplicemente il pulsante "Reinvesti". Oltre ai tuoi altri investimenti, ora hai investito di nuovo con un nuovo" . InvestmentPlan::getValueByName("contract_day") . "programma del giorno. Il minimo per reinvestire è" . InvestmentPlan::getValueByName("minimum_reinvest") . " BTC.

<b>Withdraw</b> 💼

✏ Premi \"Withdraw\" per prelevare i tuoi guadagni.

✏️ Feels free to payout your available account balance at any time once all 24 hours. The minimum to withdraw is " . InvestmentPlan::getValueByName("minimum_payout") . " BTC.

✏️ Before pushing \"Withdraw\" use the <b>/set wallet_address</b> command into the chat window and press enter to setup your Wallet-ID. Replace wallet_address by a valid bitcoin address.

✏️ Your wallet is now registered and connected with your Telegram account. Now choose the amount you want to payout as follows. Type into your chat the command: /out 0.08 (the 0.08 are just an example). As soon as you press enter now and your balance is high enough for the payout, you will get the immediate confirmation. Your money is now on the way to your personal BTC wallet within normally two hours.

✏️ To change your current payout wallet, you simply past your new wallet into the chat, that's it.

<b>Support</b> \xF0\x9F\x92\xAC

✏ " . BotSetting::getValueByName("support_chat_id"),
                'reply_markup' => $reply_markup,
                'parse_mode' => 'HTML',
            ]);

        }
    }
}
