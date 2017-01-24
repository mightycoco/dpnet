CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created DATETIME,
    modified DATETIME
);

CREATE TABLE datasource (
    uuid INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(64) NOT NULL,
    source VARCHAR(512) NOT NULL,
    trusted BOOLEAN NOT NULL DEFAULT FALSE,
    reject_zero_weight BOOLEAN NOT NULL DEFAULT FALSE,
    created DATETIME,
    modified DATETIME,
    accessed DATETIME
);

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    skey VARCHAR(64) NOT NULL,
    value VARCHAR(512) NOT NULL
);

CREATE TABLE events (
    id VARCHAR(512) PRIMARY KEY,
    datasource_id VARCHAR(512) NOT NULL,
    event_description VARCHAR(4096) NOT NULL,
    event_name VARCHAR(512) NOT NULL,
    event_approval ENUM('pending', 'approved', 'rejected') NOT NULL,
    event_start DATETIME,
    event_end DATETIME,
    created DATETIME,
    modified DATETIME,
    cover VARCHAR(512) NOT NULL,
    place_id INT NOT NULL,
    place_name VARCHAR(256) NOT NULL,
    loc_city VARCHAR(256) NOT NULL,
    loc_country VARCHAR(256) NOT NULL,
    loc_street VARCHAR(512) NOT NULL,
    loc_zip VARCHAR(64) NOT NULL,
    loc_latitude FLOAT NOT NULL,
    loc_longitude FLOAT NOT NULL
);

Event Item:
  {
      "description": "Into Madness - Festival ** Ice Edition - bald erfahrt ihr mehr!",
      "end_time": "2017-02-04T05:00:00+0100",
      "name": "Into Madness - Festival ** Ice Edition",
      "place": {
        "name": "Melodrom (official)",
        "location": {
          "city": "Kaufbeuren",
          "country": "Germany",
          "latitude": 47.899786570216,
          "longitude": 10.64230799675,
          "street": "Sudetenstraße 12",
          "zip": "87600"
        },
        "id": "134710983252535"
      },
      "start_time": "2017-02-03T08:00:00+0100",
      "id": "1244243932306604"
    },