# QA_API

## Introduction
The main aim of this small application is adding answers to questions using rest api. It has only backend layer without any gui or frontend so that the best way to test it is to use any http client i.e. Postman. 

## Tools
- Symfony 4.4. skeleton,
- FOSResBundle,
- JMSSerializerBundle,
- DoctrineFixturesBundle,
- Codeception,
- Messenger Component.

## Entities
- **Question** - representation of question,
- **Questioner** - representation of person who added question,
- **Answer** - representation of answer,
- **Answerer** - representation of person who added answer.

## Fixtures
- **QuestionerFixtures** - creates 3 questioners (authors), 
- **QuestionFixtures** - creates 10 questions added by (assigned to) 3 questioners (authors), depends on QuestionerFixtures which runs before it.

## Database

Table `question`
- **id** INT(11) - identifier, primary key,
- **questioner_id** INT(11) - reference to `questioner` table that stores author of question,
- **content** VARCHAR(255) - content of question,
- **created_at** DATETIME - date and time when question was created.

Table `questioner`
- **id** INT(11) - identifier, primary key,
- **email** VARCHAR(255) - email of question's author,
- **name** VARCHAR(255) - name of question's author,
- **nick** VARCHAR(255) - nick of question's author.

Table `answer`
- **id** INT(11) - identifier, primary key,
- **answerer_id** INT(11) - reference to `answerer` table that stores author of answer,
- **question_id** INT(11) - reference to `question` table that stores question which answer was added to,
- **content** VARCHAR(255) - content of answer,
- **created_at** DATETIME - date and time when answer was created.

Table `answerer`
- **id** INT(11) - identifier, primary key,
- **nick** VARCHAR(255) - nick of answers's author. 

## Routes

- **GET** `/questions` 
    - returns all questions data: basic without scope parameter or detailed if scope is provided, 
    - allowed query parameter: `scope`,
    - allowed parameter values: `answers`, `author`,
    - possible response codes: 200, 400, 404.
    
- **GET** `/questions/{id}`
    - returns question data for given id,
    - allowed and required parameter: `id`,
    - allowed parameter value: `integer` (id from database question table),
    - possible response codes: 200, 404.
    
- **POST** `/questions/{id}/answer`
    - adds (creates) answer to question for given id,
    - allowed parameters: `id`, `answer`, `nick`,
    - required parameters: `id`, `answer`,
    - parameter constraints: 
        - `answer` max length = 255, 
        - `nick` max length = 255,
        - forbidden words validation,
    - possible response codes: 201, 400, 404.
    
## Messages flow

Each route is handled by dedicated method in `QuestionController`. When http request is sent, controller triggers `messageBus` which dispatches new instance of message class. Each message class stores the most important data that distinguishes requested resource. 

For example, `GetQuestions` message stores `scope` parameter which is returned in handler class. Each message is processed by dedicated Message Handler. For example `GetQuestionsHandler` fetches `scope` parameter from `GetQuestions` message and does the rest of the job: grabs questions data from database, prepares json responses etc. Finally, controller returns response prepared by handler. 

Handlers have their own private methods that acts as **query** or **command** - depending on request type: 
 - `getQuestionsQuery`: returns data from database,
 - `addAnswerCommand`: validates parameters and saves new answer into database. 
 
Typical flow can be the following:   
`http request -> controller method -> messageBus dispatches message -> message handler -> final controller response`

## Tests

Routes and application flow was tested by Codeception framework. 

#### Api tests:
- **GetQuestionsCest** - tests different route response codes for `/questions`,
- **GetQuestionCest** - tests different route response codes for `/questions/{id}`,
- **AddAnswerCest** - tests different route response codes for `/questions/{id}/answer`.

#### Unit tests:
- **GetQuestionsTest** - tests methods and attributes of `GetQuestions` message class,
- **GetQuestionTest** - tests methods and attributes of `GetQuestion` message class,
- **AddAnswerTest** - tests methods and attributes of `AddAnswer` message class.


 