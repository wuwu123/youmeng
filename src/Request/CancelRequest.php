<?php


namespace Youmeng\Request;


class CancelRequest extends BaseRequest
{

    /**
     * 取消定时任务
     * @param string $taskId
     * @return array
     */
    public function cancel(string $taskId)
    {
        $this->requestModel->post('api/cancel', ['task_id', $taskId]);
        return [$this->requestModel->isOk(), $this->requestModel->getData()];
    }

}