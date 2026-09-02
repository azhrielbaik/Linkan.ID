import javax.swing.*;
public class TestScale {
    public static void main(String[] args) {
        System.out.println(UIManager.getLookAndFeel().getName());
        System.out.println("Scale: " + System.getProperty("sun.java2d.uiScale"));
    }
}
