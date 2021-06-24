## API for Equity Analytix Messenger

**In messenger admin sends two type of messages:**
- news
- messages 

Chat window has two types:
- News feed
- Privat chat

All requests should have Authorization Header with wordpress session token as following: 
```bash
ea_xHhXNN6MgWpPNAfmVwM6ukBXtl1zUV8pRhowOYKVjwc
```

Request and response body are in JSON format.
Error response body is as following:
```bash
{
    "status": "error",
    "data": {
        "code": 0,
        "message": "Token not valid or not found"
    }
}
```

## APIs

#### User

**Create user from Wp**

```bash
POST: /api/user/create
```
 Request body:
```bash
{
    "userId": (int) "1",
    "role": (string) "administrator",
    "userName": (stirng) "userName"
}
```
**Get user data**

```bash
GET: /api/user/info
```

**Get all users for admin**

```bash
GET: /api/user/all
```

#### Feed

**Get all news**

```bash
GET: api/news/admin
```

**Get news**

```bash
GET: /api/news/{timestamp}
```
Where:
```
{timestamp} - Some timestamp (int)
```

**Mark Viewed News**
```bash
POST: /api/news/{timestamp}
```
Request body:
```
[
    {
        "id":     (string News id) "8f05963a-6e82-46d3-ad1b-8a17d5504fcd",
        "userId": (int user id)  2
    },
    {
        "id":     (string News id) "33201698-8915-44d2-a432-596de6bd0fac",
        "userId": (int user id)  2
    }
]
```

**Send news message to users**
```bash
POST: /api/news/create
```
Request body:
```bash
{ 
    "status": (stirng) "bull",
    "user": (int) 1,
    "text": (string) "message text"
}
```
**Archive one News**
```bash
POST: /api/news/archive
```
Request body:
```
{
    "id":     (string News id) "8f05963a-6e82-46d3-ad1b-8a17d5504fcd",
    "userId": (int user id)  2
}
```

**Archive all News for user on current day**

```bash
POST: /api/news/archive/all
```

**Restore all News for user on current day**

```bash
POST: /api/news/restore/all
```


##### Search messages

**Search in news  for a user** 
```bash
GET: /api/news/search/{query}
```
where,
```bash
{query}  - query string to search in database
```

##### Chat settings

**Get Settings**
```bash
GET: /api/user/chat/setting
```

**Create Setting**
```bash
POST: /api/user/chat/setting/create
```
Request body:
```
{
    "userId": (int user id)  2,
    "name": (string)  "numberOfFlashes",
    "signification": (string)  "2"
}
```

**Edit Setting**
```bash
POST: /api/user/chat/setting/edit
```
Request body:
```
{
    "userId": (int user id)  2,
    "settingId": (int) 2,
    "signification": (string)  "new significaton"
}
```

#### Privat Chats

**Create privat chat room**
```
POST: /api/room/create
```
Request body:
```
{
    "participants": {
        1,
        2,
    }
}
```
Response:
```
{
    "status": "success",
    "response": {
        "participants": [
            1,
            2
        ],
        "token": "b3a12394-443c-43f4-b549-1796083526e4"
    }
}
```


**Get all messages for chat room**
```bash
GET: /api/message/chat/{roomId}
```
Where,
```bash
{roomId} - Chat room id 
```
**Create a new message in private chat room**
```bash
POST: /api/message/chat/new
```
Where,
```bash
{
    "user": (int) userId,
    "room": (int) roomId,
    "text": (string) text
}
```
