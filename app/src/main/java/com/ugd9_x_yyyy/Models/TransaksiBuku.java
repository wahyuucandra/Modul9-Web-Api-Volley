package com.ugd9_x_yyyy.Models;

import java.io.Serializable;
import java.util.List;

public class TransaksiBuku implements Serializable {
    private String noTransaksi, npm, tglTransaksi, namaMahasiswa;
    private Double totalBiaya;
    private List<DTBuku> dtBukuList;
    public Boolean isChecked = false;

    public TransaksiBuku (String noTransaksi, String npm, String tglTransaksi,
                          Double totalBiaya, String namaMahasiswa, List<DTBuku> dtBukuList){
        this.noTransaksi    = noTransaksi;
        this.npm            = npm;
        this.tglTransaksi   = tglTransaksi;
        this.totalBiaya     = totalBiaya;
        this.namaMahasiswa  = namaMahasiswa;
        this.dtBukuList     = dtBukuList;
    }

    public TransaksiBuku (String npm, Double totalBiaya){
        this.npm = npm;
        this.totalBiaya = totalBiaya;
    }

    public String getNamaMahasiswa() {
        return namaMahasiswa;
    }

    public String getNoTransaksi() {
        return noTransaksi;
    }

    public Double getTotalBiaya() {
        return totalBiaya;
    }

    public String getNpm() {
        return npm;
    }

    public String getTglTransaksi() {
        return tglTransaksi;
    }

    public List<DTBuku> getDtBukuList() {
        return dtBukuList;
    }

    public void setDtBukuList(List<DTBuku> dtBukuList) {
        this.dtBukuList = dtBukuList;
    }

    public void setTotalBiaya(Double totalBiaya) {
        this.totalBiaya = totalBiaya;
    }
}
