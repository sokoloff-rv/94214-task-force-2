<?php
namespace Taskforce\Models;

use Taskforce\Actions\ActionCancel;
use Taskforce\Actions\ActionAccept;
use Taskforce\Actions\ActionRespond;
use Taskforce\Actions\ActionDeny;
use Taskforce\Exceptions\ExceptionStatusNotExist;
use Taskforce\Exceptions\ExceptionActionNotExist;
use Taskforce\Exceptions\ExceptionNoActionAvailable;

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_WORKING = 'working';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    private int $idCustomer;
    private int $idExecutor;

    public function __construct(int $idCustomer, int $idExecutor, string $currentStatus = Task::STATUS_NEW)
    {
        if (!array_key_exists($currentStatus, $this->getStatusesMap())) {
            throw new ExceptionStatusNotExist();
        }
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
            ActionCancel::getName() => ActionCancel::getTitle(),
            ActionAccept::getName() => ActionAccept::getTitle(),
            ActionRespond::getName() => ActionRespond::getTitle(),
            ActionDeny::getName() => ActionDeny::getTitle(),
        ];
    }

    public function getNextStatus($action): string
    {
        if (!array_key_exists($action, $this->getActionsMap())) {
            throw new ExceptionActionNotExist();
        }
        $status = $this->currentStatus;
        switch ($action) {
            case ActionCancel::getName():
                $status = self::STATUS_CANCELLED;
                break;
            case ActionAccept::getName():
                $status = self::STATUS_COMPLETED;
                break;
            case ActionRespond::getName():
                $status = self::STATUS_WORKING;
                break;
            case ActionDeny::getName():
                $status = self::STATUS_FAILED;
                break;
        }
        return $status;
    }

    public function getAvailableActions($status): array
    {
        if (!array_key_exists($status, $this->getStatusesMap())) {
            throw new ExceptionStatusNotExist();
        }
        $actions = [];
        switch ($status) {
            case self::STATUS_NEW:
                $actions[] = ActionCancel::getName();
                $actions[] = ActionRespond::getName();
                break;
            case self::STATUS_WORKING:
                $actions[] = ActionAccept::getName();
                $actions[] = ActionDeny::getName();
                break;
            default:
                throw new ExceptionNoActionAvailable();
        }
        return $actions;
    }

}
