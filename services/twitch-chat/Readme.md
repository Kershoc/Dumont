# Twitch Chat Service Module

This service connects to the twitch chat irc over websockets.  The primary responsibility of this service is to relay messages between the twitch chat service and our bots ioTower.

### Event Rooms Listened On

This service will listen in the event room **speak** for messages.  

```json
{
    "event": "speak",
    "payload": "text string wish to send to chat"
}
```

### Event Rooms Broadcasted To

**twitch** 

Broadcasts the parsed twitch message to the twitch event room.

```json
{
  "event": "twitch",
  "payload": {
    "command": "",
    "tags": [],
    "irc_user": "",
    "irc_room": "",
    "options": ""
  }
}
```