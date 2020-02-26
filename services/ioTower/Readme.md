# ioTower

![](https://vignette.wikia.nocookie.net/tron/images/b/bb/I.o_tower.jpg/revision/latest/scale-to-width-down/250?cb=20081119180401)

Central Communication Service for Dumont.  This ioTower is the central comunications hub for the different microservice clients to talk with each other.  Any program that wishes to interface with their user does so at this tower.

Websocket server listens on port 6889.  Connected clients can subscribe to event rooms.  Anytime a client sends a message, that message will be checked for an event room.  If that room exists then the message will be broadcast to any clients subscribed to that event room.

The ioTower believes in free communication, so it imposes no restriction on the payloads of messages.  It just passes the message along, unaltered, to everyone listening in the event room.

##### example
```json
{
    "event":"chat",
    "payload": "Hello Everybody!"
}
```
Sending the above json to the ioTower will cause the tower to search for an event room named chat.  If the room exists in the tower, the above json will be broadcast back to everyone listening in that room.

This allows any number of clients to connect to the tower and setup communication channels to pass data around.  Since the event message is passed unaltered, clients using the tower to communicate can pass any arbitary data they wish as long as it conforms to the json format.  Messages sent to the tower that are not valid json, or do not have an event node are ignored by the tower.

## Reserved Events
#### Subscribing to Event Rooms
```json
{
    "event":"subscribe",
    "payload": ["chat", "anotherEvent", "event3"]
}
```
The payload is an array of event rooms that you wish to subscribe to.  If the event room does not exist it will be created on first join.

#### Unsubscribe
```json
{
    "event":"unsubscribe",
    "payload": ["chat", "anotherEvent", "event3"]
}
```
Unsubscribing from event rooms uses the same payload as the subscribe event, an array of event rooms you wish to stop receiving message from.

#### Broadcast
```json
{
    "event":"broadcast",
    "payload": any
}
```
Broadcast is a special event that will broadcast the supplied json to every connected client.
