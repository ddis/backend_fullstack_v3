<?php


namespace Model;

use App;
use System\Core\CI_Model;

class Open_pack extends CI_Model
{
    private $user = null;
    private $pack = null;

    public function __construct(User_model $user, Boosterpack_model $pack)
    {
        parent::__construct();

        $this->user = $user;
        $this->pack = $pack;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->user->get_wallet_balance() >= $this->pack->get_price();
    }

    /**
     * @return Item_model
     * @throws \ShadowIgniterException
     */
    public function open(): Item_model
    {
        if (!$item = $this->get_applied_item()) {
            throw new \Exception("Can't find item");
        }

        $new_bank = $this->calculate_new_bank($item);

        App::get_s()->set_transaction_repeatable_read()->execute();
        App::get_s()->start_trans()->execute();

        $res = true;
        try {
            $res &= App::get_s()->from(User_model::CLASS_TABLE)->where(['id' => $this->user->get_id()])->update([
                'likes_balance' => ($this->user->get_likes_balance() + $item->get_price()),
                'wallet_balance' => ($this->user->get_wallet_balance() - $this->pack->get_price()),
                'wallet_total_withdrawn' => ($this->user->get_wallet_total_withdrawn() + $this->pack->get_price())
            ])->execute();

            $res &= App::get_s()->is_affected();

            $this->pack->set_bank($new_bank);
            $res &= App::get_s()->is_affected() || ($new_bank === $this->pack->get_bank());

            if ($res) {
                App::get_s()->commit()->execute();

                $this->user->reload();
                $this->pack->reload();

                return $item;
            } else {
                throw new \Exception("Can't save items");
            }
        } catch (\Exception $exception) {
            App::get_s()->rollback()->execute();

            throw $exception;
        }
    }

    /**
     * @return float
     */
    protected function get_max_item_count(): float
    {
        return $this->pack->get_bank() + ($this->pack->get_price() - $this->pack->get_us());
    }

    /**
     * @return Item_model|null
     */
    protected function get_applied_item(): ?Item_model
    {
        $pack_items = Item_model::get_by_max_price($this->get_max_item_count(), $this->pack->get_id());

        if (!$pack_items) {
            return null;
        }

        $key = array_rand($pack_items);

        return $pack_items[$key] ?? null;
    }

    /**
     * @param Item_model $item
     * @return float
     */
    protected function calculate_new_bank(Item_model $item): float
    {
        $res = $this->pack->get_price() - $this->pack->get_us() - $item->get_price();

        if ($res < 0) {
            return 0;
        }

        return $res;
    }
}