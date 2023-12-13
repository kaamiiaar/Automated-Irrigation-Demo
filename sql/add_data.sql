
-- Insert data into the weather table
INSERT INTO weather (id, temp, hum) VALUES (1, '25', '60');
INSERT INTO weather (id, temp, hum) VALUES (2, '28', '55');
INSERT INTO weather (id, temp, hum) VALUES (3, '30', '50');
INSERT INTO weather (id, temp, hum) VALUES (4, '30', '65');

-- Insert data into the soilMoisture table (id, moist, min_thresh, max_thresh)
INSERT INTO soilMoisture (id, moist) values (1, 300);
INSERT INTO soilMoisture (id, moist) values (2, 400);
INSERT INTO soilMoisture (id, moist) values (3, 500);
INSERT INTO soilMoisture (id, moist) values (4, 350);

-- Insert data into the switch table
INSERT INTO switch (id, status) VALUES (1, 'off');
INSERT INTO switch (id, status) VALUES (2, 'off');
INSERT INTO switch (id, status) VALUES (3, 'off');
INSERT INTO switch (id, status) VALUES (4, 'off');
