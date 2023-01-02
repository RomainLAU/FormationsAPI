# Formations API from Romain LAURENT

## API Routes

### Formations

#### Get all formations

```sh
  GET /formations
```

#### Get one formation

```sh
  GET /formations/{id}
```

| Parameter | Type  | Description                                |
| :-------- | :---- | :----------------------------------------- |
| `id`      | `int` | **Required**. Id of the formation to fetch |

#### Get all participants from a formation

```sh
  GET /formations/{id}/participants
```

| Parameter | Type  | Description                                |
| :-------- | :---- | :----------------------------------------- |
| `id`      | `int` | **Required**. Id of the formation to fetch |

#### Create a formation

```sh
  POST /formations
```

| Parameter          | Type     | Description                                                             |
| :----------------- | :------- | :---------------------------------------------------------------------- |
| `name`             | `string` | **Required**. Name of the formation to create                           |
| `start_date`       | `string` | **Required**. Date of the start of the formation to create              |
| `end_date`         | `string` | **Required**. Date of the end of the formation to create                |
| `max_participants` | `int`    | **Required**. Number maximum of participants of the formation to create |
| `price`            | `int`    | **Required**. Price of the formation to create                          |

#### Remove a participant from a formation

```sh
  DELETE /formations/{formation_id}/participants/{participant_id}
```

| Parameter        | Type  | Description                               |
| :--------------- | :---- | :---------------------------------------- |
| `formation_id`   | `int` | **Required**. Id of the formation         |
| `participant_id` | `int` | **Required**. Id of participant to remove |

---

### Participants

#### Get all participants

```sh
  GET /participants
```

#### Get one participant

```sh
  GET /participants/{id}
```

| Parameter | Type  | Description                                  |
| :-------- | :---- | :------------------------------------------- |
| `id`      | `int` | **Required**. Id of the participant to fetch |

#### Create a participant

```sh
  POST /participants
```

| Parameter   | Type     | Description                                          |
| :---------- | :------- | :--------------------------------------------------- |
| `lastname`  | `string` | **Required**. Lastname of the participant to create  |
| `firstname` | `string` | **Required**. Firstname of the participant to create |
| `society`   | `string` | Society of the participant to create                 |
