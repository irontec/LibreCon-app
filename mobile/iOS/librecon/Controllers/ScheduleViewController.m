//
//  AgendaTabBarViewController.m
//  librecon
//
//  Created by Sergio Garcia on 16/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "ScheduleViewController.h"
#import "ScheduleCollectionCell.h"
#import "AppDelegate.h"
#import "Schedule.h"
#import "ScheduleDetailViewController.h"
#import "UIImageView+AFNetworking.h"
#import "UserDefaultsHelper.h"
#import "SVProgressHUD.h"
#import "UIColor+Librecon.h"
#import "UserDefaultsHelper.h"
#import "Speaker.h"

#define CELLS_MARGIN 2
#define COLUMNS 2

@interface ScheduleViewController() {
    
    NSMutableArray *_objectChanges;
    NSMutableArray *_sectionChanges;
    NSString *appLanguaje;
    
    BOOL isLoading;
    UIRefreshControl *refreshControl;
    
    UIView *customView;
    UISearchBar *mSearchBar;
    UIBarButtonItem *searchButton;
    BOOL isSearchBarShowed;
    
    NSString *filter;
    BOOL resetDataSources;
}

@end

@implementation ScheduleViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    
    appLanguaje = [UserDefaultsHelper getActualLanguage];
    [self menuSetup];
    [self languajeSetup];
    [self viewSetup];
    
    [self searchBarSetup];
}

- (void)searchBarSetup {
    
    isSearchBarShowed = NO;
    searchButton  = [[UIBarButtonItem alloc] initWithImage:[UIImage imageNamed:@"search"]
                                                     style:UIBarButtonItemStylePlain
                                                    target:self
                                                    action:@selector(searchButtonAction:)];
    
    self.navigationItem.rightBarButtonItem = searchButton;
    
    mSearchBar = [[UISearchBar alloc] initWithFrame:CGRectMake(0, 0, self.view.frame.size.width, 44)];
    mSearchBar.showsCancelButton = YES;
    mSearchBar.delegate = self;
    mSearchBar.translucent = YES;
    [mSearchBar setPlaceholder:NSLocalizedString(@"BUSCAR", nil)];
    mSearchBar.barStyle = UIBarStyleDefault;
    [mSearchBar setBackgroundColor:[UIColor clearColor]];
    //Cancel button title
    UIView* view = mSearchBar.subviews[0];
    for (UIView *subView in view.subviews) {
        if ([subView isKindOfClass:[UIButton class]]) {
            UIButton *cancelButton = (UIButton*)subView;
            [cancelButton setTitle:NSLocalizedString(@"CANCELAR", nil) forState:UIControlStateNormal];
        }
    }
   }

#pragma mark - UISearchBarDelegate

- (void)searchButtonAction:(id)sender {
    
    if (isSearchBarShowed) {
        [self showSearchBar:NO animated:YES becomeResponder:NO];
    } else {
        [self showSearchBar:YES animated:YES becomeResponder:YES];
    }
}

- (void)searchBarSearchButtonClicked:(UISearchBar *)searchBar {
    
    [mSearchBar resignFirstResponder];
}

- (void)searchBar:(UISearchBar *)searchBar textDidChange:(NSString *)searchText {
    
    if (searchText && searchText.length > 0) {
        filter = searchText;
    } else {
        filter = nil;
    }
    [self resetAllDataSources];
}

#pragma mark - SearchBar

- (void)showSearchBar:(BOOL)show animated:(BOOL)animated becomeResponder:(BOOL)responder {
    if (show) {
        if (isSearchBarShowed) {
            return;
        }
        if (animated) {
            [UIView animateWithDuration:0.2 animations:^{
                self.navigationItem.rightBarButtonItem = nil;
                self.navigationItem.titleView = mSearchBar;
            } completion:^(BOOL finished) {
                if (responder) {
                    [mSearchBar becomeFirstResponder];
                }
                isSearchBarShowed = YES;
            }];
        } else {
            self.navigationItem.rightBarButtonItem = nil;
            self.navigationItem.titleView = mSearchBar;
            if (responder) {
                [mSearchBar becomeFirstResponder];
            }
            isSearchBarShowed = YES;
        }
    } else {
        if (!isSearchBarShowed) {
            return;
        }
        if (animated) {
            [UIView animateWithDuration:0.2 animations:^{
                self.navigationItem.titleView = nil;
                self.navigationItem.rightBarButtonItem = searchButton;
            } completion:^(BOOL finished) {
                [mSearchBar resignFirstResponder];
                isSearchBarShowed = NO;
            }];
        } else {
            self.navigationItem.titleView = nil;
            self.navigationItem.rightBarButtonItem = searchButton;
            [mSearchBar resignFirstResponder];
            isSearchBarShowed = NO;
        }
    }
}

- (void)searchBarCancelButtonClicked:(UISearchBar *)searchBar {
    
    [searchBar setText:@""];
    filter = nil;
    [self resetAllDataSources];
    [self showSearchBar:NO animated:YES becomeResponder:NO];
}


- (void)viewWillAppear:(BOOL)animated {
    
    if (filter) {
        [self showSearchBar:YES animated:YES becomeResponder:NO];
    }
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:NOTIFI_SCHEDULES_UPDATED
                                                  object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(updatenotificationReceived:)
                                                 name:NOTIFI_SCHEDULES_UPDATED
                                               object:nil];
}

- (void)updatenotificationReceived:(NSNotification*)notification {
    
    NSDictionary *data = [notification object];
    NSNumber *res = data[@"result"];
    if ([res boolValue]) {
        [self resetAllDataSources];
    }
    isLoading = NO;
    [refreshControl endRefreshing];
    
}

- (void)viewDidAppear:(BOOL)animated {
    
    id sectionInfo = [[_currentFetchedResultsController sections] objectAtIndex:0];
    NSInteger count = [sectionInfo numberOfObjects];
    
    if (count == 0) {
        _collectionView.contentOffset = CGPointMake(0, - refreshControl.frame.size.height);
        [refreshControl beginRefreshing];
    }
    AppDelegate *app = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    [app checkSchedules];
    
}

- (void)viewWillDisappear:(BOOL)animated {
    
    if (isSearchBarShowed) {
        [self showSearchBar:NO animated:YES becomeResponder:NO];
    }
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:NOTIFI_SCHEDULES_UPDATED
                                                  object:nil];
}

- (void)dealloc {
    
    self.fetchedResultsController_DayOne = nil;
    self.fetchedResultsController_DayTwo = nil;
}

- (void)menuSetup {
    
    SWRevealViewController *revealViewController = self.revealViewController;
    if (revealViewController) {
        [self.revealButtonItem setTarget: revealViewController];
        [self.revealButtonItem setAction: @selector( revealToggle: )];
        [self.navigationController.navigationBar addGestureRecognizer:revealViewController.panGestureRecognizer];
    }
}

- (void)languajeSetup {
    
    [self setTitle:NSLocalizedString(@"AGENDA", nil)];
}

- (void)viewSetup {
    
    AppDelegate *appDelegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    
    NSManagedObjectContext *globalContext = [appDelegate managedObjectContext];
    
    _managedObjectContextDay1 = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [_managedObjectContextDay1 setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];
    
    _managedObjectContextDay2 = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [_managedObjectContextDay2 setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];
    
    _tabBar.delegate = self;
    _collectionView.dataSource = self;
    _collectionView.delegate = self;
    
    [self setBackgroundEmptyView];
    
    [self tabbarSetup];
    
    filter = nil;
    [self resetAllDataSources];
    
    // Select one datasource according to selected tabbar index
    [_tabBar setSelectedItem:_tabBar.items[0]];
    [self setSelectedDay:11];
    [self reloadModeSetup];
}

- (void)setBackgroundEmptyView {
    
    // Background View
    UILabel *label = [[UILabel alloc] initWithFrame:_collectionView.frame];
    [label setNumberOfLines:4];
    [label setText:NSLocalizedString(@"NO_DATA_SCHEDULE", nil)];
    [label setTextAlignment:NSTextAlignmentCenter];
    [label setTextColor:[UIColor tableViewBackgroundTextColor]];
    [label sizeToFit];
    [_collectionView setBackgroundView:label];
}

- (void)tabbarSetup {
    
    UITabBarItem *day11, *day12;
    
    day11 = [_tabBar items][0];
    day12 = [_tabBar items][1];
    
    [day11 setTitle:NSLocalizedString(@"DIA", nil)];
    [day12 setTitle:NSLocalizedString(@"DIA", nil)];
    
    [day11 setImage:[[UIImage imageNamed:@"tabbar_day11_unselected"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    [day11 setSelectedImage:[[UIImage imageNamed:@"tabbar_day11_selected"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    
    [day12 setImage:[[UIImage imageNamed:@"tabbar_day12_unselected"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    [day12 setSelectedImage:[[UIImage imageNamed:@"tabbar_day12_selected"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    
}

#pragma mark - Reload Setup

- (void)reloadModeSetup {
    
    refreshControl = [[UIRefreshControl alloc] init];
    [refreshControl setBackgroundColor:[UIColor whiteColor]];
    
    [refreshControl addTarget:self
                       action:@selector(refresh:)
             forControlEvents:UIControlEventValueChanged];
    
    NSMutableAttributedString *aString = [[NSMutableAttributedString alloc] initWithString:NSLocalizedString(@"CARGANDO", nil)];
    
    [aString addAttribute:NSForegroundColorAttributeName
                    value:[UIColor grayCustomColor]
                    range:NSMakeRange(0,aString.length)];
    [refreshControl setAttributedTitle:aString];
    
    [self.collectionView addSubview:refreshControl];
}

- (void)refresh:(UIRefreshControl *)refreshControl {
    
    if (isLoading) {
        return;
    }
    isLoading = YES;
    AppDelegate *delegate = (AppDelegate *)[UIApplication sharedApplication].delegate;
    [delegate checkSchedules];
    
}

- (void)setSelectedDay:(NSInteger)day {
    
    [self setTitle:[NSString stringWithFormat:NSLocalizedString(@"DIA_SELECCIONADO", nil), day]];
    switch (day) {
        case 11:
            _currentFetchedResultsController = _fetchedResultsController_DayOne;
            break;
        case 12:
            _currentFetchedResultsController = _fetchedResultsController_DayTwo;
            break;
        default:
            [self setSelectedDay:11];
            break;
    }
    [_collectionView reloadData];
}

#pragma mark - Navigation

- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([[segue identifier] isEqualToString:@"openDetail"]) {
        Schedule *schedule = (Schedule *)sender;
        ScheduleDetailViewController *scheduleDetail = (ScheduleDetailViewController *)[segue destinationViewController];
        scheduleDetail.schedule = schedule;
    }
}

#pragma mark - UITabBarDelegate

- (void)tabBar:(UITabBar *)tabBar didSelectItem:(UITabBarItem *)item {
    
    NSInteger indexOfTab = [tabBar.items indexOfObject:item];
    switch (indexOfTab) {
        case 0:
            [self setSelectedDay:11];
            break;
        case 1:
            [self setSelectedDay:12];
            break;
        default:
            [self setSelectedDay:11];
            break;
    }
}

#pragma mark - Data Control

- (void)resetAllDataSources {
    
    resetDataSources = YES;
    [NSFetchedResultsController deleteCacheWithName:CACHE_SCHEDULE_DAY11];
    [NSFetchedResultsController deleteCacheWithName:CACHE_SCHEDULE_DAY12];
    [self dataControllerSetup];
    NSInteger index = [_tabBar.items indexOfObject:[_tabBar selectedItem]];
    if (index == 0) {
        [self setSelectedDay:11];
    } else {
        [self setSelectedDay:12];
    }
}

- (void)dataControllerSetup {
    
    NSError *error;
    if (![[self fetchResultsController_DayOne] performFetch:&error]) {
        NSLog(@"First day controller. Unresolved error %@, %@", error, [error userInfo]);
    }
    if (![[self fetchResultsController_DayTwo] performFetch:&error]) {
        NSLog(@"Second day controller. Unresolved error %@, %@", error, [error userInfo]);
    }
    resetDataSources = NO;
}

- (NSFetchedResultsController *)fetchResultsController_DayOne {
    
    if (_fetchedResultsController_DayOne != nil && !resetDataSources) {
        return _fetchedResultsController_DayOne;
    }
    
    NSFetchRequest *mfetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *mentity = [NSEntityDescription entityForName:IDEN_SCHEDULE
                                               inManagedObjectContext:_managedObjectContextDay1];
    
    [mfetchRequest setEntity:mentity];
    
    NSString *myPredicate = @"(targetDate = 1)";
    if (filter) {
        myPredicate = [self addCustomFiltersTo:myPredicate];
    }
    NSPredicate *finalPredicate = [NSPredicate predicateWithFormat:myPredicate];
    [mfetchRequest setPredicate:finalPredicate];
    
    NSSortDescriptor *msort = [[NSSortDescriptor alloc]
                               initWithKey:@"startDateTime"
                               ascending:YES];
    
    [mfetchRequest setSortDescriptors:[NSArray arrayWithObject:msort]];
    [mfetchRequest setFetchBatchSize:20];
    
    NSFetchedResultsController *theFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:mfetchRequest
                                                                                                  managedObjectContext:_managedObjectContextDay1
                                                                                                    sectionNameKeyPath:nil
                                                                                                             cacheName:CACHE_SCHEDULE_DAY11];
    
    _fetchedResultsController_DayOne = theFetchedResultsController;
    
    _fetchedResultsController_DayOne.delegate = self;
    return _fetchedResultsController_DayOne;
}

- (NSFetchedResultsController *)fetchResultsController_DayTwo {
    
    if (_fetchedResultsController_DayTwo != nil && !resetDataSources) {
        return _fetchedResultsController_DayTwo;
    }
    
    NSFetchRequest *mfetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *mentity = [NSEntityDescription entityForName:IDEN_SCHEDULE
                                               inManagedObjectContext:_managedObjectContextDay2];
    
    [mfetchRequest setEntity:mentity];
    
    NSString *myPredicate = @"(targetDate = 2)";
    if (filter) {
        myPredicate = [self addCustomFiltersTo:myPredicate];
    }
    
    NSPredicate *finalPredicate = [NSPredicate predicateWithFormat:myPredicate];
    [mfetchRequest setPredicate:finalPredicate];
    
    NSSortDescriptor *msort = [[NSSortDescriptor alloc]
                               initWithKey:@"startDateTime"
                               ascending:YES];
    
    [mfetchRequest setSortDescriptors:[NSArray arrayWithObject:msort]];
    [mfetchRequest setFetchBatchSize:20];
    
    NSFetchedResultsController *theFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:mfetchRequest
                                                                                                  managedObjectContext:_managedObjectContextDay2
                                                                                                    sectionNameKeyPath:nil
                                                                                                             cacheName:CACHE_SCHEDULE_DAY12];
    
    _fetchedResultsController_DayTwo = theFetchedResultsController;
    
    _fetchedResultsController_DayTwo.delegate = self;
    return _fetchedResultsController_DayTwo;
}

- (NSString *)addCustomFiltersTo:(NSString *)defaultFilter {
    
    defaultFilter = [defaultFilter stringByAppendingString:@" AND ("];
    defaultFilter = [defaultFilter stringByAppendingString:[NSString stringWithFormat:@"(name_en contains[cd] \"%@\")", filter]];
    defaultFilter = [defaultFilter stringByAppendingString:[NSString stringWithFormat:@" OR (name_es contains[cd] \"%@\")", filter]];
    defaultFilter = [defaultFilter stringByAppendingString:[NSString stringWithFormat:@" OR (name_eu contains[cd] \"%@\")", filter]];
    defaultFilter = [defaultFilter stringByAppendingString:[NSString stringWithFormat:@" OR (description_en contains[cd] \"%@\")", filter]];
    defaultFilter = [defaultFilter stringByAppendingString:[NSString stringWithFormat:@" OR (description_es contains[cd] \"%@\")", filter]];
    defaultFilter = [defaultFilter stringByAppendingString:[NSString stringWithFormat:@" OR (description_eu contains[cd] \"%@\")", filter]];
    defaultFilter = [defaultFilter stringByAppendingString:[NSString stringWithFormat:@" OR (location contains[cd] \"%@\")", filter]];
    defaultFilter = [defaultFilter stringByAppendingString:[NSString stringWithFormat:@" OR (tagsString contains[cd] \"%@\")", filter]];
    defaultFilter = [defaultFilter stringByAppendingString:[NSString stringWithFormat:@" OR (speakersString contains[cd] \"%@\")", filter]];
    defaultFilter = [defaultFilter stringByAppendingString:@")"];
    
    return defaultFilter;
}

#pragma mark - UICollectionViewDataSource

- (NSInteger)collectionView:(UICollectionView *)collectionView numberOfItemsInSection:(NSInteger)section {
    
    id sectionInfo = [[_currentFetchedResultsController sections] objectAtIndex:section];
    NSInteger count = [sectionInfo numberOfObjects];
    
    if (count == 0) {
        [_collectionView.backgroundView setHidden:NO];
    } else {
        [_collectionView.backgroundView setHidden:YES];
    }
    
    return count;
}

- (UICollectionViewCell *)collectionView:(UICollectionView *)collectionView cellForItemAtIndexPath:(NSIndexPath *)indexPath {
    
    ScheduleCollectionCell *cell = [collectionView dequeueReusableCellWithReuseIdentifier:@"scheduleCollectionCell" forIndexPath:indexPath];
    [self configureCell:cell atIndexPath:indexPath];
    return cell;
}

- (void)configureCell:(UICollectionViewCell *)cell atIndexPath:(NSIndexPath *)indexPath {
    
    ScheduleCollectionCell *mCell = (ScheduleCollectionCell *)cell;
    Schedule *schedule = [_currentFetchedResultsController objectAtIndexPath:indexPath];
    
    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    [dateFormatter setLocale:[NSLocale currentLocale]];
    
    [dateFormatter setDateFormat:@"EEE d, HH:mm"];
    NSString *startTimeString = [[dateFormatter stringFromDate:schedule.startDateTime] capitalizedString];
    
    [dateFormatter setTimeStyle:NSDateFormatterShortStyle];
    NSString *endTimeString = [dateFormatter stringFromDate:schedule.finishDateTime];
    
    [mCell.lblDate setText:[NSString stringWithFormat:@"%@-%@", startTimeString, endTimeString]];
    
    [mCell.imgBackground setImageWithURL:[NSURL URLWithString:schedule.picUrlSquare]
                        placeholderImage:[UIImage imageNamed:@"placeholder_schedule.png"]];
    
    [mCell.lblLocation setText:schedule.location];
    
    if ([appLanguaje isEqualToString:@"en"]) {
        [mCell.lblTitle setText:schedule.name_en];
    } else if ([appLanguaje isEqualToString:@"eu"]) {
        [mCell.lblTitle setText:schedule.name_eu];
    } else {
        [mCell.lblTitle setText:schedule.name_es];
    }
    
    //Max height = 36
    CGSize maximumLabelSize = CGSizeMake(mCell.lblTitle.frame.size.width, 36);
    CGRect expectedLabelSize = [mCell.lblTitle.text boundingRectWithSize:maximumLabelSize
                                                                 options:NSStringDrawingUsesLineFragmentOrigin
                                                              attributes:@{NSFontAttributeName:mCell.lblTitle.font}
                                                                 context:nil];
    mCell.lblTitleHeightconstraint.constant = expectedLabelSize.size.height;
    
    [mCell.viewColor setBackgroundColor:[UIColor colorwithHexString:schedule.color alpha:0.85]];
    
    NSArray *speakers = [schedule.speakers allObjects];
    NSString *allSpeakers = @"";
    for (NSInteger x = 0; x < speakers.count; x++) {
        Speaker *s = [speakers objectAtIndex:x];
        if (x == 0) {
            allSpeakers = s.name;
        } else {
            allSpeakers = [allSpeakers stringByAppendingString:[NSString stringWithFormat:@" | %@", s.name]];
        }
    }
    [mCell.lblSpeaker setText:allSpeakers];
}

#pragma mark - UICollectionViewDelegate

- (void)collectionView:(UICollectionView *)collectionView didSelectItemAtIndexPath:(NSIndexPath *)indexPath {
    
    [mSearchBar resignFirstResponder];
    if (![UserDefaultsHelper getAnonymous]) {
        Schedule *schedule = [_currentFetchedResultsController objectAtIndexPath:indexPath];
        [self performSegueWithIdentifier:@"openDetail" sender:schedule];
    } else {
        [self performSegueWithIdentifier:@"requestCode" sender:nil];
    }
    [collectionView deselectItemAtIndexPath:indexPath animated:YES];
    
}

#pragma mark – UICollectionView DelegateFlowLayout

- (CGSize)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout sizeForItemAtIndexPath:(NSIndexPath *)indexPath {
    
    CGFloat height, width;
    
    width = _collectionView.frame.size.width / 2;
    width = width - CELLS_MARGIN;
    width = width - CELLS_MARGIN /2;
    
    height = width;
    /*
     id sectionInfo = [[_currentFetchedResultsController sections] objectAtIndex:0];
     NSInteger totalItems = [sectionInfo numberOfObjects];
     if (indexPath.row == totalItems - 1  && totalItems % 2 != 0) {//last cell with not pair datasource
     height = (_collectionView.frame.size.width / 2) - 2 * CELLS_MARGIN;
     width = _collectionView.frame.size.width - 2 * CELLS_MARGIN;
     } else {
     width = (_collectionView.frame.size.width / 2) - (2 * CELLS_MARGIN) + (CELLS_MARGIN/2);
     height = (_collectionView.frame.size.width / 2) - (2 * CELLS_MARGIN);
     }
     */
    return  CGSizeMake(width, height);
}

- (CGFloat)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout minimumInteritemSpacingForSectionAtIndex:(NSInteger)section {
    
    return CELLS_MARGIN;
}

- (CGFloat)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout minimumLineSpacingForSectionAtIndex:(NSInteger)section {
    
    return CELLS_MARGIN;
}


- (UIEdgeInsets)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout insetForSectionAtIndex:(NSInteger)section {
    
    return UIEdgeInsetsMake(CELLS_MARGIN, CELLS_MARGIN, CELLS_MARGIN, CELLS_MARGIN);
}

#pragma mark - NSFetchedResultsControllerDelegate

- (void)controller:(NSFetchedResultsController *)controller didChangeObject:(id)anObject atIndexPath:(NSIndexPath *)indexPath forChangeType:(NSFetchedResultsChangeType)type newIndexPath:(NSIndexPath *)newIndexPath {
    
    NSMutableDictionary *change = [NSMutableDictionary new];
    switch(type)
    {
        case NSFetchedResultsChangeInsert:
            change[@(type)] = newIndexPath;
            break;
        case NSFetchedResultsChangeDelete:
            change[@(type)] = indexPath;
            break;
        case NSFetchedResultsChangeUpdate:
            change[@(type)] = indexPath;
            break;
        case NSFetchedResultsChangeMove:
            change[@(type)] = @[indexPath, newIndexPath];
            break;
    }
    [_objectChanges addObject:change];
}

- (void)controller:(NSFetchedResultsController *)controller didChangeSection:(id )sectionInfo atIndex:(NSUInteger)sectionIndex forChangeType:(NSFetchedResultsChangeType)type {
    
    NSMutableDictionary *change = [NSMutableDictionary new];
    
    switch(type) {
        case NSFetchedResultsChangeInsert:
            change[@(type)] = @(sectionIndex);
            break;
        case NSFetchedResultsChangeDelete:
            change[@(type)] = @(sectionIndex);
            break;
    }
    
    [_sectionChanges addObject:change];
}

- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller
{
    if ([_sectionChanges count] > 0)
    {
        [self.collectionView performBatchUpdates:^{
            
            for (NSDictionary *change in _sectionChanges)
            {
                [change enumerateKeysAndObjectsUsingBlock:^(NSNumber *key, id obj, BOOL *stop) {
                    
                    NSFetchedResultsChangeType type = [key unsignedIntegerValue];
                    switch (type)
                    {
                        case NSFetchedResultsChangeInsert:
                            [self.collectionView insertSections:[NSIndexSet indexSetWithIndex:[obj unsignedIntegerValue]]];
                            break;
                        case NSFetchedResultsChangeDelete:
                            [self.collectionView deleteSections:[NSIndexSet indexSetWithIndex:[obj unsignedIntegerValue]]];
                            break;
                        case NSFetchedResultsChangeUpdate:
                            [self.collectionView reloadSections:[NSIndexSet indexSetWithIndex:[obj unsignedIntegerValue]]];
                            break;
                    }
                }];
            }
        } completion:nil];
    }
    
    if ([_objectChanges count] > 0 && [_sectionChanges count] == 0)
    {
        
        if ([self shouldReloadCollectionViewToPreventKnownIssue] || self.collectionView.window == nil) {
            // This is to prevent a bug in UICollectionView from occurring.
            // The bug presents itself when inserting the first object or deleting the last object in a collection view.
            // http://stackoverflow.com/questions/12611292/uicollectionview-assertion-failure
            // This code should be removed once the bug has been fixed, it is tracked in OpenRadar
            // http://openradar.appspot.com/12954582
            [self.collectionView reloadData];
        } else {
            [self.collectionView performBatchUpdates:^{
                for (NSDictionary *change in _objectChanges)
                {
                    [change enumerateKeysAndObjectsUsingBlock:^(NSNumber *key, id obj, BOOL *stop) {
                        
                        NSFetchedResultsChangeType type = [key unsignedIntegerValue];
                        switch (type)
                        {
                            case NSFetchedResultsChangeInsert:
                                [self.collectionView insertItemsAtIndexPaths:@[obj]];
                                break;
                            case NSFetchedResultsChangeDelete:
                                [self.collectionView deleteItemsAtIndexPaths:@[obj]];
                                break;
                            case NSFetchedResultsChangeUpdate:
                                [self.collectionView reloadItemsAtIndexPaths:@[obj]];
                                break;
                            case NSFetchedResultsChangeMove:
                                [self.collectionView moveItemAtIndexPath:obj[0] toIndexPath:obj[1]];
                                break;
                        }
                    }];
                }
            } completion:nil];
        }
    }
    [_sectionChanges removeAllObjects];
    [_objectChanges removeAllObjects];
}

- (BOOL)shouldReloadCollectionViewToPreventKnownIssue {
    
    __block BOOL shouldReload = NO;
    for (NSDictionary *change in _objectChanges) {
        [change enumerateKeysAndObjectsUsingBlock:^(id key, id obj, BOOL *stop) {
            NSFetchedResultsChangeType type = [key unsignedIntegerValue];
            NSIndexPath *indexPath = obj;
            switch (type) {
                case NSFetchedResultsChangeInsert:
                    if ([self.collectionView numberOfItemsInSection:indexPath.section] == 0) {
                        shouldReload = YES;
                    } else {
                        shouldReload = NO;
                    }
                    break;
                case NSFetchedResultsChangeDelete:
                    if ([self.collectionView numberOfItemsInSection:indexPath.section] == 1) {
                        shouldReload = YES;
                    } else {
                        shouldReload = NO;
                    }
                    break;
                case NSFetchedResultsChangeUpdate:
                    shouldReload = NO;
                    break;
                case NSFetchedResultsChangeMove:
                    shouldReload = NO;
                    break;
            }
        }];
    }
    return shouldReload;
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
