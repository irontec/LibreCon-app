//
//  User.m
//  librecon
//
//  Created by Sergio Garcia on 23/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "User.h"

@implementation User

- (void)initWithDictionary:(NSDictionary *)data {
    
    _idUser       = data[@"id"];
    _hashUser     = data[@"hash"];
    _name         = data[@"name"];
    _lastName     = data[@"lastName"];
    _email        = data[@"email"];
    _cellPhone    = data[@"cellPhone"];
    _company      = data[@"company"];
    _position     = data[@"position"];
    _picUrl       = data[@"picUrl"];
    _picUrlCircle = data[@"picUrlCircle"];
    _address      = data[@"address"];
    _location     = data[@"location"];
    _country      = data[@"country"];
    _postalCode   = data[@"postalCode"];
    _interests    = data[@"interests"];
}



- (void)encodeWithCoder:(NSCoder *)encoder {
    
    [encoder encodeObject:_idUser forKey:@"id"];
    [encoder encodeObject:_hashUser forKey:@"hash"];
    [encoder encodeObject:_name forKey:@"name"];
    [encoder encodeObject:_lastName forKey:@"lastName"];
    [encoder encodeObject:_email forKey:@"email"];
    [encoder encodeObject:_cellPhone forKey:@"cellPhone"];
    [encoder encodeObject:_company forKey:@"company"];
    [encoder encodeObject:_position forKey:@"position"];
    [encoder encodeObject:_picUrl forKey:@"picUrl"];
    [encoder encodeObject:_picUrlCircle forKey:@"picUrlCircle"];
    [encoder encodeObject:_address forKey:@"address"];
    [encoder encodeObject:_location forKey:@"location"];
    [encoder encodeObject:_country forKey:@"country"];
    [encoder encodeObject:_postalCode forKey:@"postalCode"];
    [encoder encodeObject:_interests forKey:@"interests"];
}

- (id)initWithCoder:(NSCoder *)decoder {
    
    if((self      = [super init])) {
        _idUser       = [decoder decodeObjectForKey:@"id"];
        _hashUser     = [decoder decodeObjectForKey:@"hash"];
        _name         = [decoder decodeObjectForKey:@"name"];
        _lastName     = [decoder decodeObjectForKey:@"lastName"];
        _email        = [decoder decodeObjectForKey:@"email"];
        _cellPhone    = [decoder decodeObjectForKey:@"cellPhone"];
        _company      = [decoder decodeObjectForKey:@"company"];
        _position     = [decoder decodeObjectForKey:@"position"];
        _picUrl       = [decoder decodeObjectForKey:@"picUrl"];
        _picUrlCircle = [decoder decodeObjectForKey:@"picUrlCircle"];
        _address      = [decoder decodeObjectForKey:@"address"];
        _location     = [decoder decodeObjectForKey:@"location"];
        _country      = [decoder decodeObjectForKey:@"country"];
        _position     = [decoder decodeObjectForKey:@"postalCode"];
        _interests    = [decoder decodeObjectForKey:@"interests"];
    }
    return self;
}

@end
