<?php


namespace Youmeng\Request;

class StatusRequest extends BaseRequest
{
    /**
     * 查询接口的查询推送状态
     * @param $taskId
     * @return array
     */
    public function status(string $taskId)
    {
        $this->requestModel->post('api/status', ['task_id' => $taskId]);
        return [$this->requestModel->isOk(), $this->requestModel->getData()];
    }
}
