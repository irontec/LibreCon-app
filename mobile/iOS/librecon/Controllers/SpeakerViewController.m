//
//  SpeakerViewController.m
//  librecon
//
//  Created by Sergio Garcia on 07/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "SpeakerViewController.h"
#import "UserDefaultsHelper.h"
#import "SpeakerHeaderTableViewCell.h"
#import "SpeakerDescriptionTableViewCell.h"
#import "TagsTableViewCell.h"
#import "TagCollectionViewCell.h"
#import "UIImageView+AFNetworking.h"
#import "UIColor+Librecon.h"
#import "Tag.h"
#import "LinksTableViewCell.h"
#import "LinkTableViewCell.h"
#import "Link.h"

#define TAG_HEIGHT 70

@interface SpeakerViewController () {
    
    NSString *appLanguaje;
    NSInteger totalData, linksCount;
    BOOL hasTags;
}

@end

@implementation SpeakerViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    appLanguaje = [UserDefaultsHelper getActualLanguage];
    [self languajeSetup];
    [self viewSetup];

}

- (void)languajeSetup {
    
    [self setTitle:_speaker.name];
}

- (void)viewSetup {
    
    [self.navigationController.navigationBar setTranslucent:NO];
    [self.navigationController.navigationBar setBackgroundImage:[[UIImage alloc] init]
                                                  forBarMetrics:UIBarMetricsDefault];
    [self.navigationController.navigationBar setShadowImage:[[UIImage alloc] init]];
    
    hasTags = ([[_speaker.tags allObjects] count] > 0 ? YES : NO);
    _tableView.dataSource = self;
    _tableView.delegate = self;
    UIView *footer = [[UIView alloc] initWithFrame:CGRectMake(0, 0, _tableView.frame.size.width, 5)];
    [footer setBackgroundColor:[UIColor clearColor]];
    [_tableView setTableFooterView:footer];
}



- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    
    totalData = 2;
    totalData++;//+tags
    totalData++;//enlaces title
    linksCount = [[_speaker.links allObjects] count];
    totalData = totalData + linksCount;
    return totalData;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row == 0) {
        return 140;
    } else if (indexPath.row == 1) {
        SpeakerDescriptionTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"speakerDescriptionTableViewCell"];
        
        if ([appLanguaje isEqualToString:@"en"]) {
            [cell.lblDescription setText:_speaker.description_en];
        } else if ([appLanguaje isEqualToString:@"eu"]) {
            [cell.lblDescription setText:_speaker.description_eu];
        } else {
            [cell.lblDescription setText:_speaker.description_es];
        }
        
        CGFloat defaultMargins = 50;
        
        CGRect textRect = [cell.lblDescription.text boundingRectWithSize:CGSizeMake(cell.lblDescription.frame.size.width, FLT_MAX)
                                                                 options:NSStringDrawingUsesLineFragmentOrigin
                                                              attributes:@{NSFontAttributeName:cell.lblDescription.font}
                                                                 context:nil];
        
        CGSize theSize = textRect.size;
        CGFloat resSize = theSize.height + defaultMargins + 1;
        
        NSInteger minValue = 70;
        resSize = resSize < minValue ? minValue : resSize;
        return resSize;
        
    } else if (indexPath.row == 2) {
        if (hasTags) {
            return TAG_HEIGHT;
        } else {
            return 0;
        }
    } else if (indexPath.row == 3) {
        if (linksCount > 0) {
            return 36;
        } else {
            return 0;
        }
    } else {
        return 40;
    }
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.row == 0) {
        SpeakerHeaderTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"speakerHeaderTableViewCell"];
        
        cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth;//fix ios7
        
        [cell.contentView setBackgroundColor:[UIColor navigationBarBackgroundColor]];

        [cell.imgProfile setImageWithURL:[NSURL URLWithString:_speaker.picUrlCircle]
                        placeholderImage:[UIImage imageNamed:@"placeholder_people.png"]];
        [cell.lblCompany setText:_speaker.company];
        return cell;
    } else if (indexPath.row == 1) {
        SpeakerDescriptionTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"speakerDescriptionTableViewCell"];
        
        cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth;//fix ios7

        [cell.lblInfoTitle setText:NSLocalizedString(@"INFORMACION", nil)];
        [cell.lblInfoTitle setTextColor:[UIColor navigationBarBackgroundColor]];
        
        if ([appLanguaje isEqualToString:@"en"]) {
            [cell.lblDescription setText:_speaker.description_en];
        } else if ([appLanguaje isEqualToString:@"eu"]) {
            [cell.lblDescription setText:_speaker.description_eu];
        } else {
            [cell.lblDescription setText:_speaker.description_es];
        }
        return cell;
        
    } else if (indexPath.row == 2) {
        if (hasTags) {
            TagsTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"tagsTableViewCell"];
            
            cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight;//fix ios7

            [cell.lblTagsTitle setText:NSLocalizedString(@"TAGS", nil)];
            cell.collectionView.dataSource = self;
            cell.collectionView.delegate = self;
            [cell.lblTagsTitle setTextColor:[UIColor navigationBarBackgroundColor]];
            return cell;
        } else {
            UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"empty"];
            cell.backgroundColor = [UIColor clearColor];
            return cell;
        }
    } else if (indexPath.row == 3) {
        if (linksCount > 0) {
            LinksTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"linksTableViewCell"];
            
            cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth;//fix ios7

            [cell.lblEnlacesTitle setTextColor:[UIColor navigationBarBackgroundColor]];
            [cell.lblEnlacesTitle setText:NSLocalizedString(@"ENLACES", nil)];
            return cell;
        } else {
            UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault
                                                           reuseIdentifier:@"empty"];
            cell.backgroundColor = [UIColor clearColor];
            return cell;
        }
    } else {
        Link *l = [[_speaker.links allObjects] objectAtIndex:indexPath.row - 4];
        LinkTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"linkTableViewCell"];
        
        cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth;//fix ios7
        
        [cell setLinkType:l.type];
        [cell setSelectionStyle:UITableViewCellSelectionStyleGray];
        return cell;
    }
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row >= 4) {
        Link *l = [[_speaker.links allObjects] objectAtIndex:indexPath.row - 4];
        [self openUrlInBrowser:l.url];
    }
    [_tableView deselectRowAtIndexPath:indexPath animated:YES];
}

- (void)openUrlInBrowser:(NSString *)mUrl {
    
    NSURL *url = [NSURL URLWithString:mUrl];
    [[UIApplication sharedApplication] openURL:url];
}

#pragma mark - UICollectionVIewDataSource

- (NSInteger)collectionView:(UICollectionView *)collectionView numberOfItemsInSection:(NSInteger)section {
    
    NSInteger tot = [[_speaker.tags allObjects] count];
    return tot;
}

- (UICollectionViewCell *)collectionView:(UICollectionView *)collectionView cellForItemAtIndexPath:(NSIndexPath *)indexPath {
    
    TagCollectionViewCell *cell = [collectionView dequeueReusableCellWithReuseIdentifier:@"tagCollectionViewCell"
                                                                            forIndexPath:indexPath];
    Tag *t = [[_speaker.tags allObjects] objectAtIndex:indexPath.row];
    
    if ([appLanguaje isEqualToString:@"en"]) {
        [cell.lblTagName setText:t.name_en];
    } else if ([appLanguaje isEqualToString:@"eu"]) {
        [cell.lblTagName setText:t.name_eu];
    } else {
        [cell.lblTagName setText:t.name_es];
    }
    [cell.viewBackground setBackgroundColor:[UIColor navigationBarBackgroundColor]];
    [cell.viewBackground.layer setCornerRadius:5];
    cell.viewBackground.layer.masksToBounds = YES;
    [cell.viewBackground setClipsToBounds:YES];
    
    [cell.viewTagColor setBackgroundColor:[UIColor colorwithHexString:t.color alpha:1.0]];
    return cell;
}

- (CGSize)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout sizeForItemAtIndexPath:(NSIndexPath *)indexPath {
    
    CGFloat height, width;
    
    height = collectionView.frame.size.height;
    
    Tag *t = [[_speaker.tags allObjects] objectAtIndex:indexPath.row];
    
    CGFloat defaultMargins = 32;//margenes 22
    
    UILabel *lab = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, width, height)];
    [lab setLineBreakMode:NSLineBreakByWordWrapping];
    [lab setNumberOfLines:1];
    
    if ([appLanguaje isEqualToString:@"en"]) {
        [lab setText:t.name_en];
    } else if ([appLanguaje isEqualToString:@"eu"]) {
        [lab setText:t.name_eu];
    } else {
        [lab setText:t.name_es];
    }
    
    CGRect textRect = [lab.text boundingRectWithSize:CGSizeMake(FLT_MAX, lab.frame.size.height)
                                             options:NSStringDrawingUsesLineFragmentOrigin
                                          attributes:@{NSFontAttributeName:lab.font}
                                             context:nil];
    CGSize theSize = textRect.size;
    
    if (lab.frame.size.width > theSize.width) {
        width = lab.frame.size.width;
    } else {
        width = theSize.width + defaultMargins + 1;
    }
    return  CGSizeMake(width, height);
}

- (CGFloat)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout minimumInteritemSpacingForSectionAtIndex:(NSInteger)section {
    
    return 10;
}

- (CGFloat)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout minimumLineSpacingForSectionAtIndex:(NSInteger)section {
    
    return 0;
}

- (UIEdgeInsets)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout insetForSectionAtIndex:(NSInteger)section {
    
    return UIEdgeInsetsMake(0, 0, 0, 0);
}

#pragma mark - Rotation

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
    return (toInterfaceOrientation == UIInterfaceOrientationPortrait);
}

- (BOOL)shouldAutorotate
{
    return YES;
}

- (NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskPortrait;
}

- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
{
    return UIInterfaceOrientationPortrait;
}

@end
