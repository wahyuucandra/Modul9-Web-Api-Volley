package com.ugd9_x_yyyy.Views;

import android.app.ProgressDialog;
import android.content.res.Configuration;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.SearchView;
import android.widget.Toast;

import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;
import androidx.recyclerview.widget.DefaultItemAnimator;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.ugd9_x_yyyy.API.MahasiswaAPI;
import com.ugd9_x_yyyy.Adapters.AdapterMahasiswa;
import com.ugd9_x_yyyy.Models.Mahasiswa;
import com.ugd9_x_yyyy.R;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import static com.android.volley.Request.Method.GET;


public class ViewsMahasiswa extends Fragment {
    private RecyclerView recyclerView;
    private AdapterMahasiswa adapter;
    private List<Mahasiswa> listMahasiswa;
    private View view;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        view = inflater.inflate(R.layout.fragment_views_mahasiswa, container, false);

        loadDaftarMahasiswa();
        return view;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setHasOptionsMenu(true);
    }

    @Override
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
        inflater.inflate(R.menu.menu_bar_mahasiswa, menu);
        super.onCreateOptionsMenu(menu, inflater);

        MenuItem searchItem = menu.findItem(R.id.btnSearch);

        SearchView searchView = (SearchView) searchItem.getActionView();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }
            @Override
            public boolean onQueryTextChange(String s) {
                adapter.getFilter().filter(s);
                return false;
            }
        });
    }

    @Override
    public void onPrepareOptionsMenu(Menu menu) {
        super.onPrepareOptionsMenu(menu);
        menu.findItem(R.id.btnSearch).setVisible(true);
        menu.findItem(R.id.btnAdd).setVisible(true);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();

        if (id == R.id.btnAdd) {
            Bundle data = new Bundle();
            data.putString("status", "tambah");
            TambahEditMahasiswa tambahEditMahasiswa = new TambahEditMahasiswa();
            tambahEditMahasiswa.setArguments(data);

            loadFragment(tambahEditMahasiswa);
        }
        return super.onOptionsItemSelected(item);
    }

    public void loadDaftarMahasiswa(){
        setAdapter();
        getMahasiswa();
    }

    public void setAdapter(){
        getActivity().setTitle("Data Mahasiswa");
        listMahasiswa = new ArrayList<Mahasiswa>();
        recyclerView = view.findViewById(R.id.recycler_view);
        adapter = new AdapterMahasiswa(view.getContext(), listMahasiswa, new AdapterMahasiswa.deleteItemListener() {
            @Override
            public void deleteItem(Boolean delete) {
                if(delete){
                    loadDaftarMahasiswa();
                }
            }
        });
        int orientation = getResources().getConfiguration().orientation;
        if (orientation == Configuration.ORIENTATION_LANDSCAPE) {
            GridLayoutManager gridLayoutManager = new GridLayoutManager(view.getContext(),2);
            recyclerView.setLayoutManager(gridLayoutManager);
        } else {
            RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(view.getContext());
            recyclerView.setLayoutManager(layoutManager);
        }
        recyclerView.setItemAnimator(new DefaultItemAnimator());
        recyclerView.setAdapter(adapter);
    }

    public void loadFragment(Fragment fragment) {
        FragmentManager fragmentManager = getActivity().getSupportFragmentManager();
        FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction();
        fragmentTransaction.replace(R.id.frame_view_mahasiswa,fragment)
                .addToBackStack(null)
                .commit();
    }

    public void getMahasiswa() {
        final JsonObjectRequest stringRequest = new JsonObjectRequest(GET, MahasiswaAPI.URL_SELECT
                , null, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {
                try {
                    JSONArray jsonArray = response.getJSONArray("mahasiswa");

                    if(!listMahasiswa.isEmpty())
                        listMahasiswa.clear();

                    for (int i = 0; i < jsonArray.length(); i++) {
                        JSONObject jsonObject = (JSONObject) jsonArray.get(i);

                        String npm              = jsonObject.optString("npm");
                        String nama             = jsonObject.optString("nama");
                        String jenis_kelamin    = jsonObject.optString("jenis_kelamin");
                        String prodi            = jsonObject.optString("prodi");
                        String gambar           = jsonObject.optString("gambar");
                        Mahasiswa mahasiswa = new Mahasiswa(npm, nama, jenis_kelamin, prodi, gambar);
                        listMahasiswa.add(mahasiswa);
                    }
                    adapter.notifyDataSetChanged();
                }catch (JSONException e){
                    e.printStackTrace();
                }
                Toast.makeText(view.getContext(), response.optString("message"),
                        Toast.LENGTH_SHORT).show();
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(view.getContext(), error.getMessage(),
                        Toast.LENGTH_SHORT).show();
            }
        });

        RequestQueue queue = Volley.newRequestQueue(view.getContext());
        queue.add(stringRequest);
    }
}