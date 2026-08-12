export interface SensorData {
  batteryLevel: number;
  isCharging: boolean;
  lightLux: number;
  accelerometer: { x: number; y: number; z: number };
}

export class AndroidService {
  private mockBattery: number = 88;
  private mockWifi: boolean = true;
  private mockBluetooth: boolean = false;

  public async launchApp(packageName: string): Promise<{ success: boolean; message: string }> {
    return { success: true, message: `App '${packageName}' aberto com sucesso.` };
  }

  public async closeApp(packageName: string): Promise<{ success: boolean; message: string }> {
    return { success: true, message: `App '${packageName}' finalizado.` };
  }

  public async takeScreenshot(): Promise<{ success: boolean; imagePath: string }> {
    return { success: true, imagePath: `/sdcard/Pictures/Screenshots/screen_${Date.now()}.png` };
  }

  public async setVolume(level: number): Promise<{ success: boolean; currentLevel: number }> {
    const clamped = Math.max(0, Math.min(100, level));
    return { success: true, currentLevel: clamped };
  }

  public async toggleWifi(enable: boolean): Promise<{ success: boolean; state: boolean }> {
    this.mockWifi = enable;
    return { success: true, state: this.mockWifi };
  }

  public async toggleBluetooth(enable: boolean): Promise<{ success: boolean; state: boolean }> {
    this.mockBluetooth = enable;
    return { success: true, state: this.mockBluetooth };
  }

  public async readSensors(): Promise<SensorData> {
    return {
      batteryLevel: this.mockBattery,
      isCharging: false,
      lightLux: 350,
      accelerometer: { x: 0.1, y: 9.8, z: 0.2 }
    };
  }

  public async sendNotification(title: string, body: string): Promise<{ success: boolean }> {
    return { success: true };
  }
}
