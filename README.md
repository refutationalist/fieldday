# Field Day 2023 for the KTQA Irregulars

This is attempting to manage the data capture for [Field Day 2023](http://www.arrl.org/field-day) for the *KTQA Irregualr Radio Club*, the unofficial radio club for the nearly-unofficial radio station [KTQA-LP](https://ktqa.org).

As many people who are participating with us this year are not hams, this includes some basic information about what ham radio is and how FD works.  As of 2023, this includes a mostly-functional logger.

In 2020 and 2022, we used [CloudLog](https://www.magicbug.co.uk/cloudlog/) to capture our data.   While that eventually worked, it required a lot of SQL finagling to get the data into Cabrillo file format that the ARRL site understood.  Most other field day loggers we could find seemed hard to work with and incomplete, or written for Windows users.  As we are strictly a unix shop, that wouldn't do.

## How the Logger Works

It's basically a response to things I realized while I was logging FD in 2022.  I would often type my notes in a text editor, and then copy what I copied into the logger.  Based on that, this logger gives the user an open text field.  The parser attempts to figure out the call and exchange from what you type in.  If you add some notes in between square brackets it will include those notes in the log.

I'm told this is how some other loggers work.   I'll make a video demonstrating it later on.

## Logger Requirements

The server needs:

 * PHP >8 with functional webserver
 * MariaDB, developed with 10.11
   * Notably, we use views and triggers.
   
The clients need:
  * A web browser.  Currently only tested with Firefox
  * PHP CLI >8 for automatic radio data in the logger
 
## The Basic Installation Idea

  * Create a database
  * Import ``sql/fieldday.sql`` into the database
  * Look at the scripts in ``sql/callbook`` create a local callbook for US and CA callsigns.  Import the generated schema into the database.
  * Look at the scripts in ``sql/zones`` for generating the SQL for the RAC zones used for FD.   Import the generated schema into the database.
  * This directory is the webroot.
  * Configure ``logger.ini`` appropriately.  Copy it either to ``/etc`` or a location specified in the webserver's environment variable ``LOGGERINI``.
  * Copy ``radio/radio2logger`` to any clients connected to transeivers.  See the built in help for configuration.
  * Probably good to go.
  
## HEY! There's no way to export this to ARRL!

Yeah, I'll probably get to that after FD.   I'm pushing to get this code done before I go in for hand surgery-- seven hours away at the time of this writing-- so I focused on the code that I was going to need *during* FD and figured I'd work on the code I'd need afterwards when it was over.

## TODO

  * WSJT-X import
  * testing

## Security?

Nope.

This is designed for local use only.  In our use case, we have a single wifi router that only the computers that are working Field Day are allowed to join.

## License

This code is AGPL3.  I'll get to adding appropriate headers later.

The font I'm using is (RedHat Mono)[https://github.com/RedHatOfficial/RedHatFont], which is distributed via the OFL.
