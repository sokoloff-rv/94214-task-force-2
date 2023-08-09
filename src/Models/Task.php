<?php
namespace Taskforce\Models;

use Taskforce\Actions\ActionCancel;
use Taskforce\Actions\ActionAccept;
use Taskforce\Actions\ActionRespond;
use Taskforce\Actions\ActionDeny;
use Taskforce\Exceptions\ExceptionStatusNotExist;
use Taskforce\Exceptions\ExceptionActionNotExist;
use Taskforce\Exceptions\ExceptionNoActionAvailable;

/**
 * Класс Task представляет базовую модель задания.
 */
class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_WORKING = 'working';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    private int $idCustomer;
    private int $idExecutor;

    /**
     * Task конструктор.
     *
     * @param int $idCustomer ID заказчика.
     * @param int $idExecutor ID исполнителя.
     * @param string $currentStatus Текущий статус задания (по умолчанию - STATUS_NEW).
     * @throws ExceptionStatusNotExist Исключение при отсутствии статуса.
     */
    public function __construct(int $idCustomer, int $idExecutor, string $currentStatus = Task::STATUS_NEW)
    {
        if (!array_key_exists($currentStatus, $this->getStatusesMap())) {
            throw new ExceptionStatusNotExist();
        }
        $this->idCustomer = $idCustomer;
        $this->idExecutor = $idExecutor;
        $this->currentStatus = $currentStatus;
    }

    /**
     * Получить ID заказчика.
     *
     * @return int ID заказчика.
     */
    public function getIdCustomer(): int
    {
        return $this->idCustomer;
    }

    /**
     * Получить ID исполнителя.
     *
     * @return int ID исполнителя.
     */
    public function getIdExecutor(): int
    {
        return $this->idExecutor;
    }

    /**
     * Получить карту статусов.
     *
     * @return array Карта статусов.
     */
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

    /**
     * Получить название статуса.
     *
     * @param string $alias Статус.
     * @return string Название статуса.
     */
    public static function getStatusName($alias): string
    {
        switch ($alias) {
            case self::STATUS_NEW:
                $status = 'Новое';
                break;
            case self::STATUS_CANCELLED:
                $status = 'Отменено';
                break;
            case self::STATUS_WORKING:
                $status = 'В работе';
                break;
            case self::STATUS_COMPLETED:
                $status = 'Выполнено';
                break;
            case self::STATUS_FAILED:
                $status = 'Провалено';
                break;
        }
        return $status;
    }

    /**
     * Получить карту действий.
     *
     * @return array Карта действий.
     */
    public function getActionsMap(): array
    {
        return [
            ActionCancel::getName() => ActionCancel::getTitle(),
            ActionAccept::getName() => ActionAccept::getTitle(),
            ActionRespond::getName() => ActionRespond::getTitle(),
            ActionDeny::getName() => ActionDeny::getTitle(),
        ];
    }

    /**
     * Получить следующий статус после действия.
     *
     * @param string $action Действие.
     * @return string Следующий статус.
     * @throws ExceptionActionNotExist Исключение при отсутствии действия.
     */
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

    /**
     * Получить доступные действия для статуса.
     *
     * @param string $status Статус.
     * @return array Массив доступных действий.
     * @throws ExceptionStatusNotExist Исключение при отсутствии статуса.
     * @throws ExceptionNoActionAvailable Исключение при отсутствии доступных действий.
     */
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
