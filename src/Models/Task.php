<?php
namespace Taskforce\Models;

use Taskforce\Actions\ActionCancel;
use Taskforce\Actions\ActionAccept;
use Taskforce\Actions\ActionRespond;
use Taskforce\Actions\ActionDeny;

class Task
{
    // Статусы
    const STATUS_NEW = 'new';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_WORKING = 'working';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    // Действия
    const ACTION_CANCEL = ActionCancel::class;
    const ACTION_ACCEPT = ActionAccept::class;
    const ACTION_RESPOND = ActionRespond::class;
    const ACTION_DENY = ActionDeny::class;

    private int $idCustomer;
    private int $idExecutor;

    public function __construct(int $idCustomer, int $idExecutor, string $currentStatus = Task::STATUS_NEW)
    {
        $this->idCustomer = $idCustomer;
        $this->idExecutor = $idExecutor;
        $this->currentStatus = $currentStatus;
    }

    public function getIdCustomer(): int
    {
        return $this->idCustomer;
    }

    public function getIdExecutor(): int
    {
        return $this->idExecutor;
    }

    public function getStatusesMap(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELLED => 'Отменено',
            self::STATUS_WORKING => 'В работе',
            self::STATUS_COMPLETED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено',
        ];
    }

    public function getActionsMap(): array
    {
        return [
            self::ACTION_CANCEL => ActionCancel::getTitle(),
            self::ACTION_ACCEPT => ActionAccept::getTitle(),
            self::ACTION_RESPOND => ActionRespond::getTitle(),
            self::ACTION_DENY => ActionDeny::getTitle(),
        ];
    }

    public function getNextStatus($action): string
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

    public function getAvailableActions($status): array
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
