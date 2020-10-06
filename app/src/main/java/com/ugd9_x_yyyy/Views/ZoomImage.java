package com.ugd9_x_yyyy.Views;

import android.Manifest;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.ugd9_x_yyyy.Models.Buku;
import com.ugd9_x_yyyy.R;
import com.zolad.zoominimageview.ZoomInImageView;


public class ZoomImage extends Fragment{

    private ZoomInImageView img;
    private ImageView btnBack;
    private View view;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        view = inflater.inflate(R.layout.fragment_zoom_image, container, false);
        setHasOptionsMenu(false);

        img     = view.findViewById(R.id.image_zoom);
        btnBack = view.findViewById(R.id.btnBack);

        Buku buku   = (Buku) getArguments().getSerializable("buku");
        Glide.with(view.getContext())
                .load("https://asdospbp2020.000webhostapp.com/images/"+buku.getGambar())
                .diskCacheStrategy(DiskCacheStrategy.NONE)
                .skipMemoryCache(true)
                .into(img);
        getActivity().setTitle(buku.getNamaBuku());

        btnBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                loadFragment(new ViewsBuku());
            }
        });

        return view;
    }

    public void loadFragment(Fragment fragment) {
        getActivity().setTitle("Data Buku");
        AppCompatActivity activity = (AppCompatActivity) view.getContext();
        FragmentManager fragmentManager = activity.getSupportFragmentManager();
        FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction();
        fragmentTransaction.replace(R.id.frame_zoom,fragment)
                .commit();
    }
}