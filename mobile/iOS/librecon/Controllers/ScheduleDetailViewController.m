//
//  ScheduleDetailViewController.m
//  librecon
//
//  Created by Sergio Garcia on 16/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "ScheduleDetailViewController.h"
#import "HeaderTableViewCell.h"
#import "DescriptionTableViewCell.h"
#import "SpeakerTableViewCell.h"
#import "UserDefaultsHelper.h"
#import "UIImageView+AFNetworking.h"
#import "Speaker.h"
#import "TagsTableViewCell.h"
#import "TagCollectionViewCell.h"
#import "Tag.h"
#import "UIColor+Librecon.h"
#import "LinksTableViewCell.h"
#import "LinkTableViewCell.h"
#import "Link.h"
#import "SpeakerViewController.h"
#import "SWRevealViewController.h"

#define TAG_HEIGHT 70

@interface ScheduleDetailViewController () {
    
    NSString *appLanguaje;
    NSInteger totalData, linksCount;
    BOOL hasTags;
}

@end

@implementation ScheduleDetailViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    appLanguaje = [UserDefaultsHelper getActualLanguage];
    [self languajeSetup];
    [self viewSetup];
}

- (void)languajeSetup {}

- (void)viewSetup {
    
    [_imgSchedule setImageWithURL:[NSURL URLWithString:_schedule.picUrl] placeholderImage:[UIImage imageNamed:@"placeholder_librecon.png"]];

    hasTags = ([[_schedule.tags allObjects] count] > 0 ? YES : NO);
    _tableView.dataSource = self;
    _tableView.delegate = self;
    UIView *footer = [[UIView alloc] initWithFrame:CGRectMake(0, 0, _tableView.frame.size.width, 5)];
    [footer setBackgroundColor:[UIColor clearColor]];
    [_tableView setTableFooterView:footer];
}

#pragma mark - Navigation

- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([[segue identifier] isEqualToString:@"openSpeaker"]) {
        Speaker *s = (Speaker *)sender;
        SpeakerViewController *speakerDetail = (SpeakerViewController *)[segue destinationViewController];
        speakerDetail.speaker = s;
    }
}

#pragma mark - UITableViewDataSource && UITableViewDelegate

-(void)scrollViewDidScroll:(UIScrollView *)scrollView {
    
    if (_tableView != scrollView) {
        return;
    }
    NSInteger initialOffset = 0;
    NSInteger endOffset = 250;
    CGFloat movementSpeed = 1.5f;
    
    if (scrollView.contentOffset.y >= initialOffset && scrollView.contentOffset.y <= endOffset){//up
        CGRect frame = _imgSchedule.frame;
        frame.origin.y = 0 - (int)(scrollView.contentOffset.y / movementSpeed) + (int)initialOffset/movementSpeed;
        [_imgSchedule setFrame:frame];
    } else if (scrollView.contentOffset.y <= 0) {//down
        CGRect frame = _imgSchedule.frame;
        frame.origin.y = 0;
        [_imgSchedule setFrame:frame];
    }
    
    NSInteger topPermitedOffset = 20;
    NSInteger bottomPermitedOffset = 0;
    
    if (scrollView.contentOffset.y <= -topPermitedOffset) {
        CGPoint offset = scrollView.contentOffset;
        offset.y = -topPermitedOffset;
        scrollView.contentOffset = offset;
    } else if (scrollView.contentOffset.y > 0 && scrollView.contentOffset.y >= (_tableView.contentSize.height - _tableView.bounds.size.height) + bottomPermitedOffset) {
        CGPoint offset = scrollView.contentOffset;
        
        if (_tableView.contentSize.height >= _tableView.bounds.size.height) {
            offset.y = _tableView.contentSize.height - _tableView.bounds.size.height + bottomPermitedOffset;
            scrollView.contentOffset = offset;
        } else if(offset.y >= bottomPermitedOffset){
            offset.y = bottomPermitedOffset;
            scrollView.contentOffset = offset;
        }
    }
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    
    totalData = 5 + [[_schedule.speakers allObjects] count];
    linksCount = [[_schedule.links allObjects] count];
    totalData = totalData + linksCount;
    return totalData;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row > totalData - 1 - linksCount) {
        return 40;
    } else if (indexPath.row == totalData - 1 - linksCount) {//links title
        if (linksCount > 0) {
            return 36;
        } else {
            return 0;
        }
    }else if (indexPath.row == totalData - 1 - linksCount - 1) {//tags cell
        if (hasTags) {
            TagsTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"tagsTableViewCell"];
            if (!cell) {
                cell = [[TagsTableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"tagsTableViewCell"];
            }
            return TAG_HEIGHT;
        } else {
            return 0;
        }
    } else if (indexPath.row == 0) {
        NSInteger topPermitedOffset = 20;
        return _topViewHeightConstraint.constant - topPermitedOffset;
        
    } else if (indexPath.row == 1) {
        HeaderTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"headerTableViewCell"];
        if (!cell) {
            cell = [[HeaderTableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"headerTableViewCell"];
        }
        return cell.frame.size.height;
        
    } else if (indexPath.row == 2) {
        DescriptionTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"descriptionTableViewCell"];
        if (!cell) {
            cell = [[DescriptionTableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"descriptionTableViewCell"];
        }
        
        if (!_schedule.speakers || ![[_schedule.speakers allObjects] count] > 0) {
            cell.contraintLblPonentes.constant = 0;
        }
        if ([appLanguaje isEqualToString:@"en"]) {
            [cell.lblDescription setText:_schedule.description_en];
        } else if ([appLanguaje isEqualToString:@"eu"]) {
            [cell.lblDescription setText:_schedule.description_eu];
        } else {
            [cell.lblDescription setText:_schedule.description_es];
        }
        
        CGFloat defaultMargins = 65;
        
        CGRect textRect = [cell.lblDescription.text boundingRectWithSize:CGSizeMake(cell.lblDescription.frame.size.width, FLT_MAX)
                                                                 options:NSStringDrawingUsesLineFragmentOrigin
                                                              attributes:@{NSFontAttributeName:cell.lblDescription.font}
                                                                 context:nil];
        CGSize theSize = textRect.size;
        CGFloat resSize = theSize.height + defaultMargins + 1;
        
        NSInteger minValue = 80;
        resSize = resSize < minValue ? minValue : resSize;
        return resSize;
    } else {
        SpeakerTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"speakerTableViewCell"];

        Speaker *speaker = [[_schedule.speakers allObjects] objectAtIndex:indexPath.row - 3];
        
        if ([appLanguaje isEqualToString:@"en"]) {
            [cell.lblDescription setText:speaker.description_en];
        } else if ([appLanguaje isEqualToString:@"eu"]) {
            [cell.lblDescription setText:speaker.description_eu];
        } else {
            [cell.lblDescription setText:speaker.description_es];
        }
        
        [cell.lblName setText:speaker.name];
        [cell.lblCompany setText:speaker.company];
        
        CGFloat defaultMargins = 81;
        
        NSInteger minValue = 105;
        NSInteger maxValue = 150;
        
        CGRect textRect = [cell.lblDescription.text boundingRectWithSize:CGSizeMake(cell.lblDescription.frame.size.width, maxValue)
                                                                 options:NSStringDrawingUsesLineFragmentOrigin
                                                              attributes:@{NSFontAttributeName:cell.lblDescription.font}
                                                                 context:nil];
        
        CGSize theSize = textRect.size;
        CGFloat resSize = theSize.height + defaultMargins + 1;
        
        resSize = resSize < minValue ? minValue : resSize;
        resSize = resSize > maxValue ? maxValue : resSize;
        return resSize;
    }
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.row > totalData - 1 - linksCount) {//links cells
        Link *l = [[_schedule.links allObjects] objectAtIndex:indexPath.row - (totalData - 1 - linksCount) - 1];
        LinkTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"linkTableViewCell"];
        
        cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth;//fix ios7
        
        [cell setLinkType:l.type];
        
        [cell setSelectionStyle:UITableViewCellSelectionStyleGray];
        return cell;
    } else if (indexPath.row == totalData - 1 - linksCount) {//link title cell
        if (linksCount > 0) {
            LinksTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"linksTableViewCell"];
            
            cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth;//fix ios7
            
            [cell.lblEnlacesTitle setTextColor:[UIColor navigationBarBackgroundColor]];
            [cell.lblEnlacesTitle setText:NSLocalizedString(@"ENLACES", nil)];
            return cell;
        } else {
            UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"empty"];
            cell.backgroundColor = [UIColor clearColor];
            return cell;
        }
    } else if (indexPath.row ==  totalData - 1 - linksCount - 1) {//tags cell
        if (hasTags) {
            TagsTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"tagsTableViewCell"];
            
            cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth;//fix ios7
            
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
    } else if (indexPath.row == 0) {
        UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"empty"];
        cell.backgroundColor = [UIColor clearColor];
        [cell setUserInteractionEnabled:NO];
        return cell;
    } else if (indexPath.row == 1) {
        HeaderTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"headerTableViewCell"];

        cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth;//fix ios7
        
        if ([appLanguaje isEqualToString:@"en"]) {
            [cell.lblTitle setText:_schedule.name_en];
        } else if ([appLanguaje isEqualToString:@"eu"]) {
            [cell.lblTitle setText:_schedule.name_eu];
        } else {
            [cell.lblTitle setText:_schedule.name_es];
        }
        
        NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
        [dateFormatter setLocale:[NSLocale currentLocale]];
        [dateFormatter setTimeStyle:NSDateFormatterShortStyle];
        NSString *timeString = [dateFormatter stringFromDate:_schedule.startDateTime];
        
        [cell.lblLocationTime setText:[NSString stringWithFormat:@"%@ - %@", _schedule.location, timeString]];
        
        return cell;
        
    } else if (indexPath.row == 2) {
        DescriptionTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"descriptionTableViewCell"];
        
        cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth;//fix ios7
        
        [cell.lblInfoTitle setText:NSLocalizedString(@"INFORMACION", nil)];
        [cell.lblInfoTitle setTextColor:[UIColor navigationBarBackgroundColor]];
        
        if (_schedule.speakers && [[_schedule.speakers allObjects] count] > 0) {
            [cell.lblPonentes setTextColor:[UIColor navigationBarBackgroundColor]];
            [cell.lblPonentes setText:NSLocalizedString(@"PONENTES", nil)];
        } else {
            cell.contraintLblPonentes.constant = 0;
        }
        
        if ([appLanguaje isEqualToString:@"en"]) {
            [cell.lblDescription setText:_schedule.description_en];
        } else if ([appLanguaje isEqualToString:@"eu"]) {
            [cell.lblDescription setText:_schedule.description_eu];
        } else {
            [cell.lblDescription setText:_schedule.description_es];
        }
        
        return  cell;
    } else {
        SpeakerTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"speakerTableViewCell"];
        
        cell.contentView.autoresizingMask = UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth;//fix ios7
        
        Speaker *speaker = [[_schedule.speakers allObjects] objectAtIndex:indexPath.row - 3];
        
        [cell.imgSpeaker setImageWithURL:[NSURL URLWithString:speaker.picUrlCircle] placeholderImage:[UIImage imageNamed:@"placeholder_people.png"]];
        if ([appLanguaje isEqualToString:@"en"]) {
            [cell.lblDescription setText:speaker.description_en];
        } else if ([appLanguaje isEqualToString:@"eu"]) {
            [cell.lblDescription setText:speaker.description_eu];
        } else {
            [cell.lblDescription setText:speaker.description_es];
        }
        
        [cell.lblName setText:speaker.name];
        [cell.lblCompany setText:speaker.company];
        [cell.lblCompany setTextColor:[UIColor grayCustomColor]];
        [cell.lblMore setText:NSLocalizedString(@"VER_MAS", nil)];
        [cell.lblMore setTextColor:[UIColor navigationBarBackgroundColor]];
        return  cell;
    }
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row > 2 && indexPath.row <= [[_schedule.speakers allObjects] count] + 2) {
        Speaker *s = [[_schedule.speakers allObjects] objectAtIndex:indexPath.row - 3];
        [self performSegueWithIdentifier:@"openSpeaker" sender:s];
        [_tableView deselectRowAtIndexPath:indexPath animated:YES];
    } else if (indexPath.row > totalData - 1 - linksCount) {
        Link *l = [[_schedule.links allObjects] objectAtIndex:indexPath.row - (totalData - 1 - linksCount) - 1];
        [self openUrlInBrowser:l.url];
        [_tableView deselectRowAtIndexPath:indexPath animated:YES];
    }
    [_tableView deselectRowAtIndexPath:indexPath animated:YES];
}

- (void)openUrlInBrowser:(NSString *)mUrl {
    
    NSURL *url = [NSURL URLWithString:mUrl];
    [[UIApplication sharedApplication] openURL:url];
}

#pragma mark - UICollectionVIewDataSource

- (NSInteger)collectionView:(UICollectionView *)collectionView numberOfItemsInSection:(NSInteger)section {
    
    NSInteger tot = [[_schedule.tags allObjects] count];
    return tot;
}

- (UICollectionViewCell *)collectionView:(UICollectionView *)collectionView cellForItemAtIndexPath:(NSIndexPath *)indexPath {
    
    TagCollectionViewCell *cell = [collectionView dequeueReusableCellWithReuseIdentifier:@"tagCollectionViewCell" forIndexPath:indexPath];

    Tag *t = [[_schedule.tags allObjects] objectAtIndex:indexPath.row];
    
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
    
    Tag *t = [[_schedule.tags allObjects] objectAtIndex:indexPath.row];
    
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
    
    return  CGSizeMake(width, height);}

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
