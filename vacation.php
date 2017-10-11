<?php
interface IVacation
{
    public function get($vacationID);

    public function create($vacation);

    public function update($vacation);

    public function delete($vacationID);
}


class VacationRepository implements IVacation
{
    public function get($vacationID)
    {
        /*...*/
    }

    public function create($vacation)
    {
        /*...*/
    }

    public function update($vacation)
    {
        /*...*/
    }

    public function delete($vacationID)
    {
        /*...*/
    }
}
interface IRequests
{
    public function get($requestID);

    public function create($request);

    public function update($request);

    public function delete($requestID);
}


class RequestsRepository implements IRequests
{
    public function get($requestID)
    {
        /*...*/
    }

    public function create($request)
    {
        /*...*/
    }

    public function update($request)
    {
        /*...*/
    }

    public function delete($requestID)
    {
        /*...*/
    }
}


class Vacation
{
    private $vacation_repository;

    private $id;
    private $duration;

    public function __construct(IVacation $vacation_repository, $vacationID)
    {
        $this->vacation_repository = $vacation_repository;
        list($this->id, $this->duration) = $this->vacation_repository->get($vacationID);
    }

    /**
     * Get User vacation duration
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set user's vacation remaining days
     * @param $remaining_days
     */
    public function setDuration($remaining_days)
    {
        $this->duration = $remaining_days;
        $this->vacation_repository->update(['id'=>$this->id, 'duration'=>$this->duration]);
    }

    /**
     * Get user vacation ID
     * @return mixed
     */
    public function getID()
    {
        return $this->id;
    }
}


class VacationRequests
{
    private $request_repository;

    private $request_duration;
    private $requestID;
    private $vacationID;

    public function __construct(IRequests $request_repository, $requestID)
    {
        $this->request_repository = $request_repository;
        list($this->requestID, $this->vacationID, $this->request_duration) = $this->request_repository->get($requestID);
    }

    /**
     * Get vacation duration request
     * @return mixed
     */
    public function getRequestDuration()
    {
        return $this->request_duration;
    }

    /**
     * Get vacation request ID
     * @return mixed
     */
    public function getRequestID()
    {
        return $this->requestID;
    }

    /**
     * Get vacation ID
     * @return mixed
     */
    public function getVacationID()
    {
        return $this->vacationID;
    }
}


class ManageVacation{

    private $vacation_request;
    private $vacation;

    public function __construct(VacationRequests $vacation_request)
    {
        $this->vacation_request = $vacation_request;
        $this->vacation = new Vacation($this->vacation_request->getVacationID());
    }

    /**
     * Approve user vacation request
     */
    public function approve()
    {
        $remain_days = CalcRemainingDays::decrease($this->vacation, $this->vacation_request->getRequestDuration());
        $this->vacation->setDuration($remain_days);
    }

    /**
     * Reject user vacation request
     */
    public function reject()
    {
        $this->vacation->setDuration($this->vacation->getDuration());
    }

}

class CalcRemainingDays
{
    /**
     * Decrease the number of vacation days
     * @param Vacation $vacation
     * @param $days
     * @return mixed
     */
    public static function decrease(Vacation $vacation, $days)
    {
        return $vacation->getDuration() - $days;
    }
}

