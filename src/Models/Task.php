<?php
namespace Taskforce\Models;

class Task
{
    // Статусы заданий
    const STATUS_NEW = 'new';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_WORKING = 'working';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    // Действия заказчика
    const ACTION_CANCEL = 'cancel';
    const ACTION_ACCEPT = 'accept';

    // Действия исполнителя
    const ACTION_RESPOND = 'respond';
    const ACTION_DENY = 'deny';

    private $idCustomer;
    private $idExecutor;

    public function __construct($idCustomer, $idExecutor, $currentStatus = Task::STATUS_NEW)
    {
        $this->idCustomer = $idCustomer;
        $this->idExecutor = $idExecutor;
        $this->currentStatus = $currentStatus;
    }

    public function getStatusesMap()
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELLED => 'Отменено',
            self::STATUS_WORKING => 'В работе',
            self::STATUS_COMPLETED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено',
        ];
    }

    public function getActionsMap()
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_ACCEPT => 'Принять', // в ТЗ действие называется "Выполнено", но это контринтуитивно, так как действие по определению должно быть глаголом
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_DENY => 'Отказаться',
        ];
    }

    public function getNextStatus($action)
    {
        $status = $this->currentStatus;
        switch ($action) {
            case self::ACTION_CANCEL:
                $status = self::STATUS_CANCELLED;
                break;
            case self::ACTION_ACCEPT:
                $status = self::STATUS_COMPLETED;
                break;
            case self::ACTION_RESPOND:
                $status = self::STATUS_WORKING;
                break;
            case self::ACTION_DENY:
                $status = self::STATUS_FAILED;
                break;
        }
        return $status;
    }

    public function getAvailableActions($status)
    {
        $actions = [];
        switch ($status) {
            case self::STATUS_NEW:
                $actions[] = self::ACTION_CANCEL;
                $actions[] = self::ACTION_RESPOND;
                break;
            case self::STATUS_WORKING:
                $actions[] = self::ACTION_ACCEPT;
                $actions[] = self::ACTION_DENY;
                break;
            default:
                throw new Exception("Нет доступных действий");
        }
        return $actions;
    }

}
