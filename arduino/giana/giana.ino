// =============================================================================
// GIANA Framework | Home Automation Made Easy. (LAMP || WAMP) + Arduino UNO r3.
// =============================================================================
// Copyright (C) 2013 Federico Pfaffendorf (www.federicopfaffendorf.com.ar)
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// any later version. 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with this program. If not, see http://www.gnu.org/licenses/gpl.txt
// =============================================================================
// Please, compile using Arduino 1.0.5 or greater.
// =============================================================================
// MESSAGES 
// =============================================================================
// REQUEST
//   <G(T)(PP)>             Get the value of a specific pin
// RESPONSE
//   <D(PP)(V)>             The value of a specific digital pin
//   <A(PP)(VVVV)>          The value of a specific analogue pin
// REQUEST
//   <SD(PP)(V)>            Set the value of a specific digital pin
// RESPONSE
//   <SD(PP)(V)(OK|ER)>     The result of setting a digital pin
// -----------------------------------------------------------------------------
// (T) = Pin Type: A for Analogue, D for Digital
// (PP) = Pin Number: 
//          If digital: 02, 03, 04, ... 13
//          If analogue: 00, 01, 02, 03, 04, 05
// (V) = Digital Value: 0 or 1
// (VVVV) = Analogue Value: 0000, 0001, 0002, ... 1023
// (OK|ER) = OK for OK, ER for Error
// =============================================================================
// EXAMPLES
// =============================================================================
// REQUEST 
//   <GD07>             Get the digital value of pin 7
// RESPONSE
//   <D071>             The value of digital pin 7 is 1
// REQUEST 
//   <GA00>             Get the analogue value of pin 0
// RESPONSE
//   <A000527>          The value of analogue pin 0 is 527
// REQUEST 
//   <SD131>            Set the digital value of pin 13 to 1
// RESPONSE
//   <SD131OK>          Succeed setting the value of digital pin 13 to 1
// =============================================================================
// CONSTANTS
// =============================================================================
const int SERIAL_BAUD = 9600;
const int REQUEST_MAX_LENGTH = 6;
const int RESPONSE_MAX_LENGTH = 9;
// =============================================================================
// TYPEDEFS
// =============================================================================
typedef char tRequest[REQUEST_MAX_LENGTH];
typedef char tResponse[RESPONSE_MAX_LENGTH];
// =============================================================================
// CLASS HEADER Response
// =============================================================================
class Response
{
  private:
    tResponse response_;
  public:
    Response (char action, char pinType, byte pin, int value);
    Response (char action, char pinType, byte pin, int value, boolean result);
    void getResponse (tResponse &response);
};
// =============================================================================
// CLASS HEADER Pins
// =============================================================================
class Pins
{
  public:
    static void setup();     
    static int read(char pinType, byte pin);
    static void write(char pinType, byte pin, int value);
};
// =============================================================================
// CLASS HEADER Request
// =============================================================================
class Request
{
  private:
    tRequest request_;
  public:
    Request(tRequest request);
    void process();
};
// =============================================================================
// CLASS HEADER SerialCommunication
// =============================================================================
class SerialCommunication
{
  private:
    static boolean available();
    static char read(); 
  public: 
    static void setup();
    static void processRequests();
    static void sendResponse(Response *response);
};
// =============================================================================
// CLASS DEFINITION Response
// =============================================================================
Response::Response (char action, char pinType, byte pin, int value)
{
  response_[0] = '<';
  response_[1] = pinType;  
  if (pin < 10) 
  {
    response_[2] = '0';
  }
  else 
  {
    response_[2] = '1';
    pin -= 10;    
  }
  response_[3] = pin + 48;
  if (pinType == 'D')
  {
    response_[4] = value + 48;
    response_[5] = '>';
  }
  else
  {
    response_[4] = '0';
    response_[5] = '0';
    response_[6] = '0';
    response_[7] = '0';
    if (value >= 1000) 
    {
      response_[4] = '1';
      value -= 1000;
    }    
    if ((value < 1000) && (value >= 100))
    {
      byte b = value / 100;
      response_[5] = b + 48;
      value -= (b * 100);
    }
    if ((value < 100) && (value >= 10))
    {
      byte b = value / 10;
      response_[6] = b + 48;
      value -= (b * 10);
    }
    response_[7] = value + 48;   
  }
  response_[8] = '>';
}

Response::Response (
  char action, char pinType, byte pin, int value, boolean result
)
{
  response_[0] = '<';
  response_[1] = action;
  response_[2] = pinType;
  if (pin < 10) 
  {
    response_[3] = '0';
  }
  else 
  {
    response_[3] = '1';
    pin -= 10;    
  }
  response_[4] = pin + 48;
  response_[5] = value + 48;
  if (result)
  {
    response_[6] = 'O';
    response_[7] = 'K';
  } 
  else 
  {
    response_[6] = 'E';
    response_[7] = 'R';
  }
  response_[8] = '>';
}

void Response::getResponse(tResponse &response)
{
  for (int i = 0; i < RESPONSE_MAX_LENGTH; i ++)
    response[i] = response_[i];  
}
// =============================================================================
// CLASS DEFINITION Pins
// =============================================================================
void Pins::setup()
{
  for(int pin = 2; pin <= 13; pin++)
  {
    pinMode(pin, OUTPUT);   
    digitalWrite(pin, LOW);
  }
}

int Pins::read(char pinType, byte pin)
{
  switch (pinType)  
  {
    case 'D':
        return digitalRead(pin);        
      break;
    case 'A':
        return analogRead(pin);        
      break;
  }
}

void Pins::write(char pinType, byte pin, int value)
{
  switch (pinType)  
  {
    case 'D':
        digitalWrite(pin, value);
      break;
    case 'A':
        analogWrite(pin, value);
      break;
  }
}
// =============================================================================
// CLASS DEFINITION Request
// =============================================================================
Request::Request (tRequest request)
{
  for (int i = 0; i < REQUEST_MAX_LENGTH; i ++)
    request_ [i] = request [i];
}

void Request::process()
{
  byte pin = (request_[2] - 48) * 10 + (request_[3] - 48);
  switch (request_[0])
  {
    // GET
    case 'G':
        switch (request_[1])
        {
          // Digital
          case 'D':
              SerialCommunication::sendResponse
                (new Response ('G', 'D', pin, Pins::read ('D', pin)));
            break;              
          // Analogue
          case 'A':
              SerialCommunication::sendResponse
                (new Response ('G', 'A', pin, Pins::read ('A', pin)));
            break;              
        }            
      break;
    // SET
    case 'S':
        switch (request_[1])
        {
          // Digital
          case 'D':
              Pins::write ('D', pin, request_[4] == '1' ? HIGH : LOW);
              SerialCommunication::sendResponse
                (new Response 
                  ('S', 'D', pin, request_[4] - 48, (boolean)true)
                );
            break;              
        }
      break;         
  }    
}
// =============================================================================
// CLASS DEFINITION SerialCommunication
// =============================================================================
boolean SerialCommunication::available()
{
  return(Serial.available() > 0);  
}

char SerialCommunication::read()
{
  char c[1];
  Serial.readBytes(c, 1); 
  return c[0];
}

void SerialCommunication::setup()
{
  Serial.begin(SERIAL_BAUD); 
}

void SerialCommunication::processRequests()
{
  tRequest request;
  while (SerialCommunication::available())
  {
    char charRead = SerialCommunication::read();
    if (charRead == '<')
    {
      int index = 0;
      while (charRead != '>')
      {
        if (charRead != '<')
        {
          request[index] = charRead;
          index++;
        }
        charRead = SerialCommunication::read();
      }
      Request *request_ = new Request (request);
      request_->process();
      free(request_);
    }    
  }  
}

void SerialCommunication::sendResponse(Response *response)
{
  tResponse response_;
  response->getResponse(response_);
  byte index = 0;
  while (response_[index] != '>')
  {
    Serial.print(response_[index]);
    index++; 
  }
  Serial.println(response_[index]);
  free(response);
}
// =============================================================================
// SKETCH
// =============================================================================
void setup()
{
  SerialCommunication::setup();
  Pins::setup();
}

void loop()
{ 
  SerialCommunication::processRequests();
}
// =============================================================================
