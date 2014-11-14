package com.irontec.librecon.fragments;

import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.animation.AnimatorSet;
import android.animation.ObjectAnimator;
import android.content.Context;
import android.graphics.Point;
import android.graphics.Rect;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.widget.SwipeRefreshLayout;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.DecelerateInterpolator;
import android.widget.AdapterView;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.irontec.librecon.DaoApplication;
import com.irontec.librecon.MainActivity;
import com.irontec.librecon.R;
import com.irontec.librecon.adapters.PhotoCallAdapter;
import com.irontec.librecon.api.APILibrecon;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

/**
 * Created by Asier Fernandez on 15/09/14.
 */
public class PhotoCallFragment extends BaseFragment {

    private final static String TAG = ScheduleFragment.class.getSimpleName();

    // Threads
    private GetPhotoCallPhotos mGetPhotoCallPhotos;

    // UI
    private View mRootView;
    private GridView mGridView;
    private PhotoCallAdapter mPhotoCallAdapter;
    private ImageView mExpandedImage;
    private LinearLayout mExpandedLayout;
    private Animator mCurrentAnimator;
    private int mShortAnimationDuration;
    private List<String> thumbnails;
    private List<String> urls;
    private SwipeRefreshLayout mSwipePhotocall;

    public PhotoCallFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Tracker tracker = ((DaoApplication) getActivity().getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.fragments.PhotoCallFragment");
        tracker.send(new HitBuilders.AppViewBuilder().build());
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        mRootView = inflater.inflate(R.layout.fragment_photo_call, container, false);

        mGridView = (GridView) mRootView.findViewById(R.id.photo_call_grid);
        mPhotoCallAdapter = new PhotoCallAdapter(getActivity(), new ArrayList<String>(), new ArrayList<String>());
        mGridView.setAdapter(mPhotoCallAdapter);
        mGridView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                zoomImageFromThumb(view, (String) parent.getItemAtPosition(position));
            }
        });

        mSwipePhotocall = (SwipeRefreshLayout) mRootView.findViewById(R.id.swipe_photocall);

        mShortAnimationDuration = getResources().getInteger(
                android.R.integer.config_shortAnimTime);

        setupSwipes();

        mGetPhotoCallPhotos = new GetPhotoCallPhotos(getActivity());
        mGetPhotoCallPhotos.execute();

        return mRootView;
    }

    private void setupSwipes() {
        mSwipePhotocall.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                //TODO llamada a head para ver cambios
                mGetPhotoCallPhotos = new GetPhotoCallPhotos(getActivity());
                mGetPhotoCallPhotos.execute();
            }
        });
        int orange = getResources().getColor(R.color.librecon_main_orange);
        int orangeDark = getResources().getColor(R.color.librecon_main_orange_dark);
        mSwipePhotocall.setColorSchemeColors(orange, orangeDark, orange, orangeDark);
    }

    public class GetPhotoCallPhotos extends AsyncTask<Void, Void, JSONObject> {

        private Context context;

        public GetPhotoCallPhotos(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            mSwipePhotocall.setRefreshing(true);
        }

        @Override
        protected JSONObject doInBackground(Void... params) {
            JSONObject response = downloadDataFromAPI();
            if (response != null) {
                return response;
            }
            return null;
        }

        private JSONObject downloadDataFromAPI() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.get(APILibrecon.PHOTO_CALL);
                if (response != null && !response.has("errorCode")) {
                    return response;
                } else if (response != null) {
                    handleErrorResponse(response);
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            } catch (IOException ex) {
                ex.printStackTrace();
            }
            return null;
        }

        private boolean isNetworkAvailable() {
            ConnectivityManager connectivityManager
                    = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
            return activeNetworkInfo != null && activeNetworkInfo.isConnected();
        }

        private void handleErrorResponse(JSONObject response) throws JSONException {
            if (response.getInt("errorCode") == 500) {
                Toast.makeText(getActivity(), R.string.http_500, Toast.LENGTH_LONG).show();
            } /*else if (response.getInt("errorCode") == 401) {
                logout();
            }*/
        }

        @Override
        protected void onPostExecute(JSONObject result) {
            super.onPostExecute(result);
            if (result != null) {
                try{
                    thumbnails = new ArrayList<String>();
                    urls = new ArrayList<String>();
                    JSONArray photos = result.getJSONObject("data").getJSONArray("photos");
                    for (int i = 0; i < photos.length(); i++) {
                        thumbnails.add(photos.getJSONObject(i).getString("thumbnailUrl"));
                        urls.add(photos.getJSONObject(i).getString("url"));
                    }
                    mPhotoCallAdapter.addItems(thumbnails, urls);
                } catch (JSONException jsEx) {
                    jsEx.printStackTrace();
                }
            }
            mSwipePhotocall.setRefreshing(false);
        }
    }

    private void zoomImageFromThumb(final View thumbView, String url) {
        // If there's an animation in progress, cancel it
        // immediately and proceed with this one.
        if (mCurrentAnimator != null) {
            mCurrentAnimator.cancel();
        }

        // Load the high-resolution "zoomed-in" image.
        mExpandedImage = (ImageView) mRootView.findViewById(R.id.expanded_image);
        mExpandedLayout = (LinearLayout) mRootView.findViewById(R.id.expanded_layout);
        if (url != null && !url.isEmpty())
            Picasso.with(getActivity()).load(url).into(mExpandedImage);

        // Calculate the starting and ending bounds for the zoomed-in image.
        // This step involves lots of math. Yay, math.
        final Rect startBounds = new Rect();
        final Rect finalBounds = new Rect();
        final Point globalOffset = new Point();

        // The start bounds are the global visible rectangle of the thumbnail,
        // and the final bounds are the global visible rectangle of the container
        // view. Also set the container view's offset as the origin for the
        // bounds, since that's the origin for the positioning animation
        // properties (X, Y).
        thumbView.getGlobalVisibleRect(startBounds);
        mRootView.findViewById(R.id.container)
                .getGlobalVisibleRect(finalBounds, globalOffset);
        startBounds.offset(-globalOffset.x, -globalOffset.y);
        finalBounds.offset(-globalOffset.x, -globalOffset.y);

        // Adjust the start bounds to be the same aspect ratio as the final
        // bounds using the "center crop" technique. This prevents undesirable
        // stretching during the animation. Also calculate the start scaling
        // factor (the end scaling factor is always 1.0).
        float startScale;
        if ((float) finalBounds.width() / finalBounds.height()
                > (float) startBounds.width() / startBounds.height()) {
            // Extend start bounds horizontally
            startScale = (float) startBounds.height() / finalBounds.height();
            float startWidth = startScale * finalBounds.width();
            float deltaWidth = (startWidth - startBounds.width()) / 2;
            startBounds.left -= deltaWidth;
            startBounds.right += deltaWidth;
        } else {
            // Extend start bounds vertically
            startScale = (float) startBounds.width() / finalBounds.width();
            float startHeight = startScale * finalBounds.height();
            float deltaHeight = (startHeight - startBounds.height()) / 2;
            startBounds.top -= deltaHeight;
            startBounds.bottom += deltaHeight;
        }

        // Hide the thumbnail and show the zoomed-in view. When the animation
        // begins, it will position the zoomed-in view in the place of the
        // thumbnail.
        thumbView.setAlpha(0f);
        mExpandedImage.setVisibility(View.VISIBLE);
        mExpandedLayout.setVisibility(View.VISIBLE);

        // Set the pivot point for SCALE_X and SCALE_Y transformations
        // to the top-left corner of the zoomed-in view (the default
        // is the center of the view).
        mExpandedImage.setPivotX(0f);
        mExpandedImage.setPivotY(0f);

        // Construct and run the parallel animation of the four translation and
        // scale properties (X, Y, SCALE_X, and SCALE_Y).
        AnimatorSet set = new AnimatorSet();
        set
                .play(ObjectAnimator.ofFloat(mExpandedImage, View.X,
                        startBounds.left, finalBounds.left))
                .with(ObjectAnimator.ofFloat(mExpandedImage, View.Y,
                        startBounds.top, finalBounds.top))
                .with(ObjectAnimator.ofFloat(mExpandedImage, View.SCALE_X,
                        startScale, 1f)).with(ObjectAnimator.ofFloat(mExpandedImage,
                View.SCALE_Y, startScale, 1f));
        set.setDuration(mShortAnimationDuration);
        set.setInterpolator(new DecelerateInterpolator());
        set.addListener(new AnimatorListenerAdapter() {
            @Override
            public void onAnimationEnd(Animator animation) {
                mCurrentAnimator = null;
            }

            @Override
            public void onAnimationCancel(Animator animation) {
                mCurrentAnimator = null;
            }
        });
        set.start();
        mCurrentAnimator = set;

        // Upon clicking the zoomed-in image, it should zoom back down
        // to the original bounds and show the thumbnail instead of
        // the expanded image.
        final float startScaleFinal = startScale;
        mExpandedImage.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (mCurrentAnimator != null) {
                    mCurrentAnimator.cancel();
                }

                // Animate the four positioning/sizing properties in parallel,
                // back to their original values.
                AnimatorSet set = new AnimatorSet();
                set.play(ObjectAnimator
                        .ofFloat(mExpandedImage, View.X, startBounds.left))
                        .with(ObjectAnimator
                                .ofFloat(mExpandedImage,
                                        View.Y,startBounds.top))
                        .with(ObjectAnimator
                                .ofFloat(mExpandedImage,
                                        View.SCALE_X, startScaleFinal))
                        .with(ObjectAnimator
                                .ofFloat(mExpandedImage,
                                        View.SCALE_Y, startScaleFinal));
                set.setDuration(mShortAnimationDuration);
                set.setInterpolator(new DecelerateInterpolator());
                set.addListener(new AnimatorListenerAdapter() {
                    @Override
                    public void onAnimationEnd(Animator animation) {
                        thumbView.setAlpha(1f);
                        mExpandedImage.setVisibility(View.GONE);
                        mExpandedLayout.setVisibility(View.GONE);
                        mCurrentAnimator = null;
                    }

                    @Override
                    public void onAnimationCancel(Animator animation) {
                        thumbView.setAlpha(1f);
                        mExpandedImage.setVisibility(View.GONE);
                        mExpandedLayout.setVisibility(View.GONE);
                        mCurrentAnimator = null;
                    }
                });
                set.start();
                mCurrentAnimator = set;
            }
        });
    }
}
