@block @block_calendar_month
Feature: Enable the calendar block in a course and test it's functionality
  In order to enable the calendar block in a course
  As a teacher
  I can add the calendar block to a course

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email | idnumber |
      | teacher1 | Teacher | 1 | teacher1@example.com | T1 |
      | student1 | Student | 1 | student1@example.com | S1 |
      | student2 | Student | 2 | student2@example.com | S2 |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |
      | student2 | C1 | student |

  Scenario: Add the block to a the course
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    When I add the "Calendar" block
    Then "Calendar" "block" should exist

  @javascript
  Scenario: View a site event in the calendar block
    Given I log in as "admin"
    And I create a calendar event with form data:
      | id_eventtype | Site |
      | id_name | Site Event |
    And I log out
    When I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add the "Calendar" block
    And I hover over today in the mini-calendar block
    Then I should see "Site Event"

  @javascript
  Scenario: View a course event in the calendar block
    Given I log in as "teacher1"
    And I create a calendar event with form data:
      | Type of event | course        |
      | Course        | Course 1      |
      | Event title   | Course Event  |
    When I am on "Course 1" course homepage with editing mode on
    And I add the "Calendar" block
    And I hover over today in the mini-calendar block
    Then I should see "Course Event"

  @javascript
  Scenario: View a user event in the calendar block
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add the "Calendar" block
    And I create a calendar event with form data:
      | id_eventtype | User |
      | id_name | User Event |
    When I am on "Course 1" course homepage
    And I hover over today in the mini-calendar block
    Then I should see "User Event"

  @javascript
  Scenario: View a group event in the calendar block
    Given the following "groups" exist:
      | name    | course | idnumber |
      | Group 1 | C1     | G1       |
      | Group 2 | C1     | G2       |
    And the following "group members" exist:
      | user     | group   |
      | student1 | G1 |
      | student2 | G2 |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | id_groupmode | Separate groups |
      | id_groupmodeforce | Yes |
    And I press "Save and display"
    And I turn editing mode on
    And I add the "Calendar" block
    And I click on "Full calendar" "link"
    And I set the field "course" to "C1"
    And I create a calendar event:
      | Type of event | group       |
      | Group         | Group 1     |
      | Event title   | Group Event |
    And I log out
    Then I log in as "student1"
    And I am on "Course 1" course homepage
    And I hover over today in the mini-calendar block
    And I should see "Group Event"
    And I log out
    And I log in as "student2"
    And I am on "Course 1" course homepage
    And I should not see "Group Event"
