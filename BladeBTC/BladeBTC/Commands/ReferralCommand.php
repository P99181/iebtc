<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\BotSetting;
use BladeBTC\Models\Referrals;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class ReferralCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "referral";

	/**
	 * @var string Command Description
	 */
	protected $description = "Referral menu";

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
		$this->replyWithChatAction(['action' => Actions::TYPING]);


		/**
		 * Verify user
		 */
		$user = new Users($id);
		if ($user->exist() == false) {

			$this->triggerCommand('start');

		} else {

			/**
			 * Keyboard
			 */
			$keyboard = [
				["My balance " . Btc::Format($user->getBalance()) . " \xF0\x9F\x92\xB0"],
				["Invest \xF0\x9F\x92\xB5", "Withdraw \xE2\x8C\x9B"],
				["Reinvest \xE2\x86\xA9", "Help \xE2\x9D\x93"],
				["My Team \xF0\x9F\x91\xAB"],
			];

			$reply_markup = $this->telegram->replyKeyboardMarkup([
				'keyboard'          => $keyboard,
				'resize_keyboard'   => true,
				'one_time_keyboard' => false,
			]);


			$this->replyWithMessage([
				'text'         => "<b>Sistema Ref:</b>

Usa questo link per invitare i tuoi amici e guadagnare il 10% dai loro investimenti.

<b>Ecco il tuo link da condividere con gli amici:</b>
https://t.me/" . BotSetting::getValueByName("app_name") . "?start=" . $user->getReferralLink() . "

<b>Statistiche</b>

Ref Totali : <b>" . Referrals::getTotalReferrals($user->getTelegramId()) . "</b>

Membero | Attivo | Investito
" . Referrals::getTotalReferrals($user->getTelegramId()) . " | " . Referrals::getActiveReferrals($user->getTelegramId()) . " | " . Btc::Format(Referrals::getReferralsInvest($user->getTelegramId())) . " BTC
",
				'reply_markup' => $reply_markup,
				'parse_mode'   => 'HTML',
			]);


		}
	}
}
