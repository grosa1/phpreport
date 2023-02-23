<?php
/*
 * Copyright (C) 2009-2013 Igalia, S.L. <info@igalia.com>
 *
 * This file is part of PhpReport.
 *
 * PhpReport is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhpReport is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhpReport.  If not, see <http://www.gnu.org/licenses/>.
 */


/** File for TasksFacade
 *
 *  This file just contains {@link TasksFacade}.
 *
 * @filesource
 * @package PhpReport
 * @subpackage facade
 * @author Jorge López Fernández <jlopez@igalia.com>
 * @author Jacobo Aragunde Pérez <jaragunde@igalia.com>
 */

include_once(PHPREPORT_ROOT . '/model/facade/action/CreateTasksAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/DeleteReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/PartialUpdateTasksAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetPersonalSummaryByLoginDateAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetGlobalUsersProjectsReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetGlobalUsersStoriesReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetGlobalProjectsUsersReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetGlobalProjectsCustomersReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetGlobalUsersProjectsCustomersReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetUserProjectWorkReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetProjectTtypeReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetUserProjectReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetProjectUserWeeklyHoursReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetProjectUserCustomerReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetProjectUserStoryReportAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetTaskBlockConfigurationAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetTasksFilteredAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetUserTasksByDateAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/GetUserTasksByLoginDateAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/IsWriteAllowedForDateAction.php');
include_once(PHPREPORT_ROOT . '/model/facade/action/SetTaskBlockConfigurationAction.php');
include_once(PHPREPORT_ROOT . '/model/dao/DAOFactory.php');
include_once(PHPREPORT_ROOT . '/model/vo/TaskVO.php');
include_once(PHPREPORT_ROOT . '/model/vo/ProjectVO.php');
include_once(PHPREPORT_ROOT . '/model/vo/UserVO.php');

/** Tasks Facade
 *
 *  This Facade contains the functions related to Task objects.
 *
 * @package PhpReport
 * @subpackage facade
 */
abstract class TasksFacade {

    /** Create Task Function
     *
     *  This function is used for creating a new Task and get its generated ID.
     *
     * @param TaskVO $task the Task value object we want to create. It will be
     * updated with the autogenerated task ID field in case of success.
     * @return OperationResult the result {@link OperationResult} with information about operation status
     */
    static function CreateReport(TaskVO $task) {
        return TasksFacade::CreateReports(array($task));
    }

    /** Create Tasks Function
     *
     *  This function is used for creating an array of new Tasks and get their
     *  generated IDs. If an error occurs, it stops creating.
     *
     * @param array $tasks the Task value objects we want to create. The objects
     * contained in the array will be updated with the autogenerated task ID
     * field in case of success.
     * @return OperationResult the result {@link OperationResult} with information about operation status
     */
    static function CreateReports($tasks) {
        $action = new CreateTasksAction($tasks);
        return $action->execute();
    }

    /** Delete Task Function
     *
     *  This function is used for deleting a Task.
     *
     * @param TaskVO $task the Task value object we want to delete.
     * @return int it just indicates if there was any error (<i>-1</i>) or not (<i>0</i>).
     * @throws {@link SQLQueryErrorException}
     */
    static function DeleteReport(TaskVO $task) {
        $action = new DeleteReportAction($task);
        return $action->execute();
    }

    /** Delete Reports Function
     *
     *  This function is used for deleting an array of Tasks.
     *  If an error occurs, it stops deleting.
     *
     * @param array $tasks the Task value objects we want to delete.
     * @return int it just indicates if there was any error (<i>-1</i>) or not (<i>0</i>).
     * @throws {@link SQLQueryErrorException}
     */
    static function DeleteReports($tasks) {
        foreach((array)$tasks as $task)
            if ((TasksFacade::DeleteReport($task)) == -1)
                return -1;

        return 0;
    }

    /** Partial Update Task Function
     *
     *  This function is used for partially updating a Task.
     *
     * @param DirtyTaskVO $task the Task value object we want to update. Must be
     *        a DirtyTaskVO object which contains also the information about
     *        which fields must be updated.
     * @return int it just indicates if there was any error (<i>-1</i>) or not (<i>0</i>).
     * @throws {@link SQLQueryErrorException}, {@link SQLUniqueViolationException}
     */
    static function PartialUpdateReport(DirtyTaskVO $task) {
        return TasksFacade::PartialUpdateReports(array($task));
    }

    /** Partial Update Tasks Function
     *
     *  This function is used for partially updating an array of Tasks.
     *  If an error occurs, it stops updating.
     *
     * @param array $tasks the Task value objects we want to update. Must be
     *        a DirtyTaskVO object which contains also the information about
     *        which fields must be updated.
     * @return int it just indicates if there was any error (<i>-1</i>) or not (<i>0</i>).
     * @throws {@link SQLQueryErrorException}, {@link SQLUniqueViolationException}
     */
    static function PartialUpdateReports($tasks) {
        $action = new PartialUpdateTasksAction($tasks);
        return $action->execute();
    }

    /** Get Personal Work Summary by Login and Date Function
     *
     *  This action is used for retrieving data about work done by a User on a date,
     *  its week and its month by his/her login (user id also works).
     *
     * @param UserVO $user the User whose summary we want to retrieve (it
     * must have the login or id).
     * @param DateTime $date the date on which we want to compute the summary.
     * @return array an array with the values related to the keys 'day', 'week' and 'month'.
     */
    static function GetPersonalSummaryByLoginDate(UserVO $userVO, DateTime $date) {

        $action = new GetPersonalSummaryByLoginDateAction($userVO, $date);

        return $action->execute();

    }

    /** Get User Tasks by date Function
     *
     *  This function is used for retrieving all Tasks related to a User on a date by his/her login.
     *
     * @param UserVO $userVO the User whose Tasks we want to retieve.
     * @param DateTime $date the date whose tasks we want to retrieve.
     * @return array an array with value objects {@link TaskVO} with their properties set to the values from the rows
     * and ordered ascendantly by their init time.
     */
    static function GetUserTasksByLoginDate(UserVO $userVO, DateTime $date) {

    $action = new GetUserTasksByLoginDateAction($userVO, $date);

    return $action->execute();
    }

    /** Get User Tasks by date Function
     *
     *  This function is used for retrieving all Tasks related to a User on a date.
     *
     * @param UserVO $userVO the User whose Tasks we want to retieve.
     * @param DateTime $date the date whose tasks we want to retrieve.
     * @return array an array with value objects {@link TaskVO} with their properties set to the values from the rows
     * and ordered ascendantly by their database internal identifier.
     */
    static function GetUserTasksByDate(UserVO $userVO, DateTime $date) {

    $action = new GetUserTasksByDateAction($userVO, $date);

    return $action->execute();

    }

    /** Get Tasks Filtered function.
     *
     * This function retrieves tasks filtered by multiple fields.
     *
     * Multiple fields can be used as filters; to disable a filter, a NULL value
     * has to be passed on that parameter.
     *
     * @param DateTime $filterStartDate start date to filter tasks. Those tasks
     *        having a date equal or later than this one will be returned. NULL
     *        to deactivate filtering by this field.
     * @param DateTime $filterEndDate end date to filter tasks. Those tasks
     *        having a date equal or sooner than this one will be returned. NULL
     *        to deactivate filtering by this field.
     * @param boolean $telework filter tasks by their telework field.
     *        NULL to deactivate filtering by this field.
     * @param boolean $onsite filter tasks by their onsite field.
     *        NULL to deactivate filtering by this field.
     * @param string $filterText string to filter tasks by their description
     *        field. Tasks with a description that contains this string will
     *        be returned. NULL to deactivate filtering by this field.
     * @param string $type string to filter projects by their type field.
     *        Only projects with a type field that matches completely with this
     *        string will be returned. NULL to deactivate filtering by this
     *        field.
     * @param int $userId id of the user whose tasks will be filtered. NULL to
     *        deactivate filtering by this field.
     * @param int $projectId id of the project which tasks will be filtered by.
     *        NULL to deactivate filtering by this field.
     * @param int $customerId id of the customer whose tasks will be filtered.
     *        NULL to deactivate filtering by this field.
     * @param int $taskStoryId id of the story inside the XP tracker which tasks
     *        will be filtered. NULL to deactivate filtering by this field.
     * @param string $filterStory string to filter tasks by their story field.
     *        Tasks with a story that contains this string will be returned.
     *        NULL to deactivate filtering by this field.
     * @param boolean $emptyText filter tasks by the presence, or absence, of
     *        text in the description field. NULL to deactivate this field; if
     *        not NULL, the parameter $filterText will be ignored.
     * @param boolean $emptyStory filter tasks by the presence, or absence, of
     *        text in the story field. NULL to deactivate this field; if
     *        not NULL, the parameter $filterStory will be ignored.
     * @return array an array with value objects {@link TaskVO} with their
     *         properties set to the values from the rows and ordered
     *         ascendantly by their database internal identifier.
     */
    static function GetTasksFiltered($filterStartDate = NULL, $filterEndDate = NULL,
            $telework = NULL, $onsite = NULL, $filterText = NULL, $type = NULL, $userId = NULL,
            $projectId = NULL, $customerId = NULL, $taskStoryId = NULL,
            $filterStory = NULL, $emptyText = NULL, $emptyStory = NULL) {

        $action = new GetTasksFilteredAction($filterStartDate, $filterEndDate,
                $telework, $onsite, $filterText, $type, $userId, $projectId,
                $customerId, $taskStoryId, $filterStory, $emptyText, $emptyStory);
        return $action->execute();
    }

    /**  Get Global Users Stories Report Action
     *
     *  This function is used for retrieving information about Tasks done by Users for each Story. We can pass dates
     *  with optional parameters <var>$init</var> and <var>$end</var> if we want to retrieve information about only an interval.
     *
     * @param DateTime $init the initial date of the interval whose Tasks report we want to retrieve.
     * @param DateTime $end the ending date of the interval whose Tasks report we want to retrieve.
     * @return array an array with the resulting rows of computing the extra hours as associative arrays (they contain a field
     * <i>add_hours</i> with that result and fields for the grouping fields <i>userid</i> and <i>story</i>).
     */
    static function GetGlobalUsersStoriesReport(DateTime $init = NULL, DateTime $end = NULL) {

    $action = new GetGlobalUsersStoriesReportAction($init, $end);

    return $action->execute();

    }

    /**  Get Global Users Projects Report Action
     *
     *  This function is used for retrieving information about Tasks done by Users for each Project. We can pass dates
     *  with optional parameters <var>$init</var> and <var>$end</var> if we want to retrieve information about only an interval.
     *
     * @param DateTime $init the initial date of the interval whose Tasks report we want to retrieve.
     * @param DateTime $end the ending date of the interval whose Tasks report we want to retrieve.
     * @return array an array with the resulting rows of computing the extra hours as associative arrays (they contain a field
     * <i>add_hours</i> with that result and fields for the grouping fields <i>userid</i> and <i>projectid</i>).
     */
    static function GetGlobalUsersProjectsReport(DateTime $init = NULL, DateTime $end = NULL) {

    $action = new GetGlobalUsersProjectsReportAction($init, $end);

    return $action->execute();

    }

    /** Get weekly hours worked per week on a given project
     *
     * This function is used to retrieve details of hours worked by all users on a given project
     * <var>$projectVO</var>
     *
     * @param ProjectVO $projectVO
     * @param DateTime|null $init
     * @param DateTime|null $end
     * @return mixed
     * @throws null
     */
    static function GetProjectUserWeeklyHoursReport(ProjectVO $projectVO, DateTime $init = NULL, DateTime $end = NULL) {
        $action = new GetProjectUserWeeklyHoursReportAction($projectVO, $init, $end);

        return $action->execute();
    }

    /**  Get User Project Report Action
     *
     *  This function is used for retrieving information about Tasks done by a User for each Project. We can pass dates
     *  with optional parameters <var>$init</var> and <var>$end</var> if we want to retrieve information about only an interval.
     *
     * @param UserVO $userVO the User whose tasks' report we want to retrieve.
     * @param DateTime $init the initial date of the interval whose Tasks report we want to retrieve.
     * @param DateTime $end the ending date of the interval whose Tasks report we want to retrieve.
     * @return array an associative array with the worked hours data, with the User login as first level key and
     * the Project description as second level one.
     */
    static function GetUserProjectReport(UserVO $userVO, DateTime $init = NULL, DateTime $end = NULL) {

        $action = new GetUserProjectReportAction($userVO, $init, $end);

        return $action->execute();

    }

    /**  Get Global Projects Customers Report Action
     *
     *  This function is used for retrieving information about Tasks done for each Project and each Customer. We can pass dates
     *  with optional parameters <var>$init</var> and <var>$end</var> if we want to retrieve information about only an interval.
     *
     * @param DateTime $init the initial date of the interval whose Tasks report we want to retrieve.
     * @param DateTime $end the ending date of the interval whose Tasks report we want to retrieve.
     * @return array an associative array with the worked hours data, with the Project name as first level key and the Customer name
     * as second level one.
     */
    static function GetGlobalProjectsCustomersReport(DateTime $init = NULL, DateTime $end = NULL) {

    $action = new GetGlobalProjectsCustomersReportAction($init, $end);

    return $action->execute();

    }

    /**  Get Global Projects Users Report Action
     *
     *  This function is used for retrieving information about Tasks done for each Project by each User. We can pass dates
     *  with optional parameters <var>$init</var> and <var>$end</var> if we want to retrieve information about only an interval.
     *
     * @param DateTime $init the initial date of the interval whose Tasks report we want to retrieve.
     * @param DateTime $end the ending date of the interval whose Tasks report we want to retrieve.
     * @return array an associative array with the worked hours data, with the Project name as first level key and the User login
     * as second level one.
     */
    static function GetGlobalProjectsUsersReport(DateTime $init = NULL, DateTime $end = NULL) {

        $action = new GetGlobalProjectsUsersReportAction($init, $end);

        return $action->execute();

    }

    /**  Get Project User Customer Report Action
     *
     *  This function is used for retrieving information about worked hours in Tasks related to a Project, grouped by User and Customer.
     *
     * @param ProjectVO $projectVO the Project whose Tasks report we want to retrieve.
     * @param DateTime $init the initial date of the interval whose Tasks report we want to retrieve.
     * @param DateTime $end the ending date of the interval whose Tasks report we want to retrieve.
     * @return array an associative array with the worked hours data, with the User login as first level key and the Customer id
     * as second level one.
     */
    static function GetProjectUserCustomerReport(ProjectVO $projectVO, DateTime $init = NULL, DateTime $end = NULL) {

    $action = new GetProjectUserCustomerReportAction($projectVO, $init, $end);

    return $action->execute();

    }

    /**  Get Project User Story Report Action
     *
     *  This function is used for retrieving information about worked hours in Tasks related to a Project, grouped by User and Story.
     *
     * @param ProjectVO $projectVO the Project whose Tasks report we want to retrieve.
     * @param DateTime $init the initial date of the interval whose Tasks report we want to retrieve.
     * @param DateTime $end the ending date of the interval whose Tasks report we want to retrieve.
     * @return array an associative array with the worked hours data, with the User login as first level key and the Story
     * as second level one.
     */
    static function GetProjectUserStoryReport(ProjectVO $projectVO, DateTime $init = NULL, DateTime $end = NULL) {

    $action = new GetProjectUserStoryReportAction($projectVO, $init, $end);

    return $action->execute();

    }

    /** Get Global Users Projects Customers report Action
     *
     *  This function is used for retrieving information about Tasks done by Users. We can pass dates with optional
     *  parameters <var>$init</var> and <var>$end</var> if we want to retrieve information about only an interval.
     *
     * @param DateTime $init the initial date of the interval whose Tasks report we want to retrieve.
     * @param DateTime $end the ending date of the interval whose Tasks report we want to retrieve.
     * @return array an array with the resulting rows of computing the worked hours as associative arrays (they contain a field
     * <i>add_hours</i> with that result and fields for the grouping fields <i>userid</i>, <i>projectid</i> and <i>customerid</i>).
     */
    static function GetGlobalUsersProjectsCustomersReport(DateTime $init = NULL, DateTime $end = NULL) {

    $action = new GetGlobalUsersProjectsCustomersReportAction($init, $end);

    return $action->execute();

    }

    /** Get User Project Work report Action
     *
     *  This function is used for for retrieving information about Tasks done by a User. We can pass dates with optional
     *  parameters <var>$init</var> and <var>$end</var> if we want to retrieve information about only an interval.
     *
     * @param DateTime $init the initial date of the interval whose Tasks report we want to retrieve.
     * @param DateTime $end the ending date of the interval whose Tasks report we want to retrieve.
     * @return array an associative array with the worked hours data, with the Project description as first level key and
     * the Customer id as second level one.
     */
    static function GetUserProjectWorkReport(UserVO $userVO, DateTime $init = NULL, DateTime $end = NULL) {

    $action = new GetUserProjectWorkReportAction($userVO, $init, $end);

    return $action->execute();

    }

    /** Get Project Ttype report Action
     *
     *  This function is used for retrieving information about Tasks related to a Project, grouped by task type (ttype).
     *
     * @param ProjectVO $projectVO the Project whose Tasks report we want to retrieve.
     * @return array an array with the resulting rows of computing the worked hours as associative arrays (they contain a field
     * <i>add_hours</i> with that result and fields for the grouping fields <i>projectid</i> and <i>ttype</i>).
     */
    static function GetProjectTtypeReport(ProjectVO $projectVO) {

    $action = new GetProjectTtypeReportAction($projectVO);

    return $action->execute();

    }

    /** IsWriteAllowedForDateAction Action
     *
     * This function is used to know the status of the configuration regarding
     * the ability to save tasks on a specific date.
     *
     * @param DateTime $date the date to check on the configuration.
     * @return boolean true if task save is enabled for that date, false
     *         otherwise.
     */
    static function IsWriteAllowedForDate(DateTime $date) {

        $action = new IsWriteAllowedForDateAction($date);
        return $action->execute();
    }

    /** SetTaskBlockConfiguration Action
     *
     * Change PhpReport configuration to allow or prevent writing tasks based on
     * the date of those tasks.
     *
     * @param boolean $dayLimitEnabled Enable of disable a day limit for tasks,
     *        so tasks older than a certain number of days would be blocked.
     * @param int $numberOfDays Set the number of days in the past when tasks
     *        tasks cannot be altered.
     * @param boolean $dateLimitEnabled Enable of disable a limit date for tasks,
     *        so tasks before that date would be blocked.
     * @param DateTime $date Tasks before this date would be blocked if
     *        $dateLimitEnabled is set.
     * @return boolean returns wether changes were saved or not.
     */
    static function SetTaskBlockConfiguration($dayLimitEnabled, $numberOfDays,
            $dateLimitEnabled, $date) {

        $action = new SetTaskBlockConfigurationAction($dayLimitEnabled,
                $numberOfDays, $dateLimitEnabled, $date);
        return $action->execute();
    }

    /** GetTaskBlockConfiguration Action
     *
     * Return all the values implicated in the configuration of task block by
     * date.
     *
     * @return array "dayLimitEnabled" returns whether task block by day limit is
     *         enabled or not.
     *         "numberOfDays" returns the number of days configured as day
     *         limit. May be null.
     *         "dateLimitEnabled" returns whether task block by date is enabled
     *         or not.
     *         "date" returns the date before which tasks may not be edited. May
     *         be null.
     */
    static function GetTaskBlockConfiguration() {

        $action = new GetTaskBlockConfigurationAction();
        return $action->execute();
    }

    /** Retrieve the date of the last task for a user
     *
     * @param  $userVO User to check
     * @param  $referenceDate The date to start searching backwars.
     * @return DateTime The date of the last task of that user before $referenceDate,
     *         or NULL if the user doesn't have any tasks yet.
     */
    static function getLastTaskDate(UserVO $userVO, DateTime $referenceDate) {
        //There is no action object for this task:
        //creating one action per facade method is an overkill if there
        //are no plugins for that action; will be created only if needed.
        $dao = DAOFactory::getTaskDAO();
        return $dao->getLastTaskDate($userVO->getId(), $referenceDate);
    }

    /** Retrieve list of days with no task in a period of time
     *
     * @param int $userId Id of the user to check
     * @param  $start The start date of the period.
     * @param  $end The end date of the period.
     * @return array The list of week days in the period that doesn't have any task.
     */
    static function getEmptyDaysInPeriod(UserVO $userVO, DateTime $start, DateTime $end) {
        $dao = DAOFactory::getTaskDAO();

        return $dao->getEmptyDaysInPeriod($userVO->getId(), $start, $end);
    }

    /** Retrieve the id for the configured VACATIONS_PROJECT.
     *
     * The project id will be retrieved based on the configuration parameters
     * VACATIONS_PROJECT_ID and VACATIONS_PROJECT, in that order. The latter is
     * considered deprecated and will log a warning.
     * @return int The ID of the configured VACATIONS_PROJECT or null, if it's
     * not properly set up.
     */
    static function GetVacationsProjectId(): ?int {
        //There is no action object for this task:
        //creating one action per facade method is an overkill if there
        //are no plugins for that action; will be created only if needed.
        $dao = DAOFactory::getTaskDAO();
        return $dao->getVacationsProjectId();
    }
}
