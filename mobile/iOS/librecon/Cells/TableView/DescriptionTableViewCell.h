//
//  DescriptionTableViewCell.h
//  librecon
//
//  Created by Sergio Garcia on 23/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface DescriptionTableViewCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UILabel *lblInfoTitle;
@property (weak, nonatomic) IBOutlet UILabel *lblDescription;
@property (weak, nonatomic) IBOutlet UILabel *lblPonentes;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *contraintLblPonentes;

@end
