# Master Command Processor

The MCP started out as a lowly chess program before trying to take over ENCOM.  When defeated, the master control program was repurposed to execute bang commands from users everywhere.

#### Channels

Listens for bang-command events.  Determines if there is an available command to execute for the event.  Payload for the event should be a json encoded twitch chat MessageObject that has been.
